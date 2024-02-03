<?php

if (!function_exists('stats_covariance')) {
    function stats_covariance($valuesA, $valuesB)
    {
        $countA = count($valuesA);
        $countB = count($valuesB);

        if ($countA != $countB) {
            trigger_error('Arrays with different sizes: countA=' . $countA . ', countB=' . $countB, E_USER_WARNING);
        }

        if ($countA < 0) {
            trigger_error('Empty arrays', E_USER_WARNING);
        }

        $meanA = array_sum($valuesA) / floatval($countA);
        $meanB = array_sum($valuesB) / floatval($countB);
        $add = 0.0;

        for ($pos = 0; $pos < $countA; $pos++) {
            $valueA = $valuesA[$pos];
            if (!is_numeric($valueA)) {
                trigger_error('Not numerical value in array A at position ' . $pos . ', value=' . $valueA, E_USER_WARNING);
            }

            $valueB = $valuesB[$pos];
            if (!is_numeric($valueB)) {
                trigger_error('Not numerical value in array B at position ' . $pos . ', value=' . $valueB, E_USER_WARNING);
            }

            $difA = $valueA - $meanA;
            $difB = $valueB - $meanB;
            $add += ($difA * $difB);
        } // for

        return $add / floatval($countA);
    }
}

/* if (!function_exists('stats_harmonic_mean')) {
    function stats_harmonic_mean(array ...$a)
    {
        $num_args = func_num_args();
        for ($i = 0; $i < $num_args; $i++) {
            $a += 1 / func_get_arg($i);
        }
        return $num_args / $a;
    }
} */

if (!function_exists('stats_kurtosis')) {
    function stats_kurtosis($values)
    {
        $numValues = count($values);
        if ($numValues == 0) {
            return 0.0;
        }

        $mean = array_sum($values) / floatval($numValues);
        $add2 = 0.0;
        $add4 = 0.0;

        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
            $dif = $value - $mean;
            $dif2 = $dif * $dif;
            $add2 += $dif2;
            $add4 += ($dif2 * $dif2);
        } // foreach values

        $variance = $add2 / floatval($numValues);
        return ($add4 * $numValues) / ($add2 * $add2) - 3.0;
    }
}

if (!function_exists('stats_skew')) {
    function stats_skew($values)
    {
        $numValues = count($values);

        if ($numValues == 0) {
            return 0.0;
        }

        $mean = array_sum($values) / floatval($numValues);

        $add2 = 0.0;
        $add3 = 0.0;
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }

            $dif = $value - $mean;
            $add2 += ($dif * $dif);
            $add3 += ($dif * $dif * $dif);
        } // foreach values

        $variance = $add2 / floatval($numValues);

        return ($add3 / floatval($numValues)) / pow($variance, 3 / 2.0);
    }
}

if (!function_exists('stats_standard_deviation')) {
    /**
     * This user-land implementation follows the implementation quite strictly;
     * it does not attempt to improve the code or algorithm in any way. It will
     * raise a warning if you have fewer than 2 values in your array, just like
     * the extension does (although as an E_USER_WARNING, not E_WARNING).
     * 
     * @param array $a 
     * @param bool $sample [optional] Defaults to false
     * @return float|bool The standard deviation or false on error.
     */
    function stats_standard_deviation(array $a, $sample = false)
    {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((float) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
            --$n;
        }
        return sqrt($carry / $n);
    }
}

if (!function_exists('stats_stat_correlation')) {
    /**
     * Returns the Pearson correlation coefficient of two data sets
     * 
     * @param array $x
     * @param array $b
     * @return float
     */
    function stats_stat_correlation(array $x, array $y)
    {
        $length = count($x);
        $mean1 = array_sum($x) / $length;
        $mean2 = array_sum($y) / $length;

        $a = 0;
        $b = 0;
        $axb = 0;
        $a2 = 0;
        $b2 = 0;

        for ($i = 0; $i < $length; $i++) {
            $a = $x[$i] - $mean1;
            $b = $y[$i] - $mean2;
            $axb = $axb + ($a * $b);
            $a2 = $a2 + pow($a, 2);
            $b2 = $b2 + pow($b, 2);
        }

        $corr = $axb / sqrt($a2 * $b2);
        return $corr;
    }
}

if (!function_exists('stats_stat_percentile')) {
    /**
     * Returns the percentile value
     */
    function stats_stat_percentile(array $arr, float $perc): float
    {
        $count = count($arr);
        sort($arr, SORT_NUMERIC);
        $low = floor(0.01 * $perc * $count);
        //$max = floor(0.01 * (100 - $perc) * $count);
        $percvar = $arr[$low];

        return $percvar;
    }
}
