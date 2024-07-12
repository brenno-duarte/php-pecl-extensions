<?php

require_once dirname(__DIR__) . "/statistic.php";

use PHPUnit\Framework\TestCase;

class StatsTest extends TestCase
{
    public function testFunctions()
    {
        $this->assertEquals(0, stats_covariance([123], [789]));
        $this->assertEquals(2.18978102189781, stats_harmonic_mean(1, 2, 3, 4, 5));
        $this->assertEquals(true, stats_kurtosis([1, 2, 3, 4, 5]));
        $this->assertEquals(0, stats_skew([1, 2, 3, 4, 5]));
        $this->assertEquals(1.4142135623730951, stats_standard_deviation([1, 2, 3, 4, 5]));
        $this->assertEquals(0.7977240352174656, stats_stat_correlation([1, 2, 3, 4, 5], [1, 2, 3, 8, 5]));
        $this->assertEquals(6547, stats_stat_percentile([3, 4, 5, 4645, 32, 6547], 90));
    }
}
