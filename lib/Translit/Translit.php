<?php

namespace PeclPolyfill\Translit;

/**
 * @internal
 */
final class Translit {
	private static $filters = [
		'uppercase_greek' => ['PeclPolyfill\\Translit\\UppercaseGreek', 'convert'],
		'greek_uppercase' => ['PeclPolyfill\\Translit\\UppercaseGreek', 'convert'],
		'jamo_transliterate' => ['PeclPolyfill\\Translit\\JamoTransliterate', 'convert'],
		'cyrillic_transliterate' => ['PeclPolyfill\\Translit\\CyrillicTransliterate', 'convert'],
		'cyrillic_transliterate_bulgarian' => ['PeclPolyfill\\Translit\\CyrillicTransliterateBulgarian', 'convert'],
		'uppercase_latin' => ['PeclPolyfill\\Translit\\UppercaseLatin', 'convert'],
		'latin_uppercase' => ['PeclPolyfill\\Translit\\UppercaseLatin', 'convert'],
		'diacritical_remove' => ['PeclPolyfill\\Translit\\DiacriticalRemove', 'convert'],
		'lowercase_cyrillic' => ['PeclPolyfill\\Translit\\LowercaseCyrillic', 'convert'],
		'cyrillic_lowercase' => ['PeclPolyfill\\Translit\\LowercaseCyrillic', 'convert'],
		'han_transliterate' => ['PeclPolyfill\\Translit\\HanTransliterate', 'convert'],
		'normalize_punctuation' => ['PeclPolyfill\\Translit\\NormalizePunctuation', 'convert'],
		'remove_punctuation' => ['PeclPolyfill\\Translit\\RemovePunctuation', 'convert'],
		'spaces_to_underscore' => ['PeclPolyfill\\Translit\\SpacesToUnderscore', 'convert'],
		'uppercase_cyrillic' => ['PeclPolyfill\\Translit\\UppercaseCyrillic', 'convert'],
		'cyrillic_uppercase' => ['PeclPolyfill\\Translit\\UppercaseCyrillic', 'convert'],
		'lowercase_greek' => ['PeclPolyfill\\Translit\\LowercaseGreek', 'convert'],
		'greek_lowercase' => ['PeclPolyfill\\Translit\\LowercaseGreek', 'convert'],
		'normalize_superscript_numbers' => ['PeclPolyfill\\Translit\\NormalizeSuperscriptNumbers', 'convert'],
		'normalize_subscript_numbers' => ['PeclPolyfill\\Translit\\NormalizeSubscriptNumbers', 'convert'],
		'normalize_numbers' => ['PeclPolyfill\\Translit\\NormalizeNumbers', 'convert'],
		'normalize_superscript' => ['PeclPolyfill\\Translit\\NormalizeSuperscript', 'convert'],
		'normalize_subscript' => ['PeclPolyfill\\Translit\\NormalizeSubscript', 'convert'],
		'normalize_ligature' => ['PeclPolyfill\\Translit\\NormalizeLigature', 'convert'],
		'decompose_special' => ['PeclPolyfill\\Translit\\DecomposeSpecial', 'convert'],
		'decompose_currency_signs' => ['PeclPolyfill\\Translit\\DecomposeCurrencySigns', 'convert'],
		'decompose' => ['PeclPolyfill\\Translit\\Decompose', 'convert'],
		'greek_transliterate' => ['PeclPolyfill\\Translit\\GreekTransliterate', 'convert'],
		'lowercase_latin' => ['PeclPolyfill\\Translit\\LowercaseLatin', 'convert'],
		'latin_lowercase' => ['PeclPolyfill\\Translit\\LowercaseLatin', 'convert'],
		'hebrew_transliterate' => ['PeclPolyfill\\Translit\\HebrewTransliterate', 'convert'],
		'hangul_to_jamo' => ['PeclPolyfill\\Translit\HangulToJamo', 'convert'],
		'compact_underscores' => ['PeclPolyfill\\Translit\CompactUnderscores', 'convert'],
	];

	public static function transliterate_filters_get() {
		return array_keys(self::$filters);
	}

	public static function transliterate($string, array $filter_list, $charset_in = null, $charset_out = null) {
		if (strlen($charset_in)) {
			$string = iconv($charset_in, 'ucs-2le', $string);
		}

		$string = array_values(unpack('S*', $string));
		foreach ($filter_list as $filter) {
			if (isset(self::$filters[$filter])) {
				$string = call_user_func(self::$filters[$filter], $string);
			}
		}
		array_unshift($string, 'S*');
		$string = call_user_func_array('pack', $string);

		if (strlen($charset_out)) {
			$string = iconv('ucs-2le', $charset_out.'//IGNORE', $string);
		}

		return $string;
	}
}
