<?php

namespace PeclPolyfill\Translit;

class CompactUnderscores {
	public static function convert($in) {
		/* Determine initial string length */
		$in_length = count($in);
		$out = [];
		$initial = 1;
		$count   = 0;

		/* Loop over input array */
		for ($i = 0; $i < $in_length; $i++) {
			if ($initial) {
				if ($in[$i] != ord('_')) {
					$tmp_out[] = $in[$i];
					$initial = 0;
				}
			} else if ($in[$i] == ord('_')) {
				if ($count == 0) {
					$out[] = $in[$i];
					$count++;
				}
			} else {
				$out[] = $in[$i];
				$count = 0;
			}
		}
		while (count($out) && end($out) === ord('_')) {
			array_pop($out);
		}
		return $out;
	}
}
