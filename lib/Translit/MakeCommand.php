<?php

namespace PeclPolyfill\Translit;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class MakeCommand extends Command {
	protected function configure() {
		$this->setName('make');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$resources = __DIR__.'/../Resources';
		$source = __DIR__;
		copy($resources.'/fragments/Translit.php.head', $source.'/Translit.php');
		foreach ((new Finder())->files()->in($resources.'/data')->name('*.tr') as $file) {
			(new Converter($file->getRealPath(), $source))->convert();
		}
		file_put_contents($source.'/Translit.php', file_get_contents($resources.'/fragments/Translit.php.tail'), FILE_APPEND);
	}
}
