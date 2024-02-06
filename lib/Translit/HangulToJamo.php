<?php

namespace PeclPolyfill\Translit;

class HangulToJamo {
	public static function convert($in) {
		$SBase = 0xac00; $LBase = 0x1100; $VBase = 0x1161;
		$TBase = 0x11a7; $SCount = 11172; $VCount = 21; $TCount = 28;

		$NCount = $VCount * $TCount;

		/* Determine initial string length */
		$in_length = count($in);
		$out = [];

		/* Loop over input array */
		for ($i = 0; $i < $in_length; $i++) {
			$SIndex = $in[$i] - $SBase;
			if ($SIndex >= 0 && $SIndex < $SCount) {
				$L = $LBase + $SIndex / $NCount;
				$V = $VBase + ($SIndex % $NCount) / $TCount;
				$T = $TBase + $SIndex % $TCount;
				$out[] = $L;
				$out[] = $V;
				if ($T != $TBase) {
					$out[] = $T;
				}
			} else {
				$out[] = $in[$i];
			}
		}
		return $out;
	}
}
