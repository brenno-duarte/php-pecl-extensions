<?php

namespace PeclPolyfillTest;

use PeclPolyfill\Translit\Translit;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class TranslitTest extends TestCase {
	public function testProvider() {
		$tests = [];

		// find test files
		foreach ((new Finder())->files()->in(dirname(__DIR__).'/lib/Translit/Resources/tests')->name('*.phpt') as $file) {
			$state = null;
			$lines = file($file->getRealPath());
			$test = [];

			// parse test file
			foreach ($lines as $line) {
				$line = rtrim($line, "\r\n");
				if (preg_match('/^--([A-Z]+)--$/', $line, $match)) {
					$state = strtolower($match[1]);
				} elseif (isset($state)) {
					$test[$state][] = $line;
				}
			}

			// add to test set
			$test = array_map(function($x) { return implode("\n", $x); }, $test);
			$tests[$test['test']] = [$test['file'], $test['expect']];
		}
		return $tests;
	}

	/**
	 * @dataProvider testProvider
	 */
	public function testTransliterate($file, $expect) {
		// remove php tags
		$file = preg_replace('/^<\?php\s*|\s*\?>$/', '', $file);

		// replace transliterate calls
		$file = preg_replace('/\b(?:transliterate|transliterate_filters_get)\s*\(\b/', Translit::class.'::\0', $file);

		// replace directory constant
		$file = preg_replace('/\b__DIR__\b/', var_export(__DIR__.'/../Resources/tests', true), $file);

		// run test source
		ob_start();
		eval($file);
		$actual = ob_get_clean();

		// compare results
		$this->assertEquals(trim($expect), trim($actual));
	}
}
