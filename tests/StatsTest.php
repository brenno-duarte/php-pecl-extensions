<?php

require_once dirname(__DIR__) . '/statistic.php';

use PHPUnit\Framework\TestCase;

class StatsTest extends TestCase
{
    public function testFunctions()
    {
        //var_dump(stats_cdf_weibull(3, 2, 1, 1));exit;

        $this->assertEquals(4.5, stats_absolute_deviation([10, 12, 23, 23, 16, 23, 21, 16]));
        $this->assertEquals(0, stats_covariance([123], [789]));
        $this->assertEquals(2.18978102189781, stats_harmonic_mean(1, 2, 3, 4, 5));
        $this->assertEquals(true, stats_kurtosis([1, 2, 3, 4, 5]));
        $this->assertEquals(0, stats_skew([1, 2, 3, 4, 5]));
        $this->assertEquals(1.4142135623730951, stats_standard_deviation([1, 2, 3, 4, 5]));
    }

    public function testCdfFunctions()
    {
        $this->assertEquals(0.5915320722379241, stats_cdf_beta(0.5, 2.5, 3.0, 1));
        $this->assertEquals(0.171875, stats_cdf_binomial(3, 10, 0.5, 1));
        $this->assertEquals(0.8788810584091566, stats_cdf_cauchy(2.5, 0, 1, 1));
        $this->assertEquals(0.8283565441441634, stats_cdf_chisquare(5, 3, 1));
        $this->assertEquals(0.6321205588285577, stats_cdf_exponential(2, 0.5, 1));
        $this->assertEquals(0.9825923204932251, stats_cdf_f(3, 10, 20, 1));
        $this->assertEquals(0.8010749436559342, stats_cdf_gamma(3, 2, 1, 1));
        $this->assertEquals(0.9323323583816936, stats_cdf_laplace(2, 0, 1, 1));
        $this->assertEquals(0.8807970779778823, stats_cdf_logistic(2, 0, 1, 1));
        $this->assertEquals(0.36328125, stats_cdf_negative_binomial(3, 5, 0.5, 1));
        $this->assertEquals(0.15999186997087156, stats_cdf_noncentral_chisquare(3, 5, 2, 1));
        $this->assertEquals(1.170635067996267, stats_cdf_noncentral_f(3, 5, 7, 2, 1));
        $this->assertEquals(2.0371377394061163, stats_cdf_noncentral_t(2, 10, 3, 1));
        $this->assertEquals(0.9772499371127437, stats_cdf_normal(2, 0, 1, 1));
        $this->assertEquals(0.7575761331330659, stats_cdf_poisson(3, 2.5, 1));
        $this->assertEquals(1.9665258860017123, stats_cdf_t(2, 10, 1));
        $this->assertEquals(0.25, stats_cdf_uniform(2, 1, 5, 1));
        $this->assertEquals(0.9998765901959134, stats_cdf_weibull(3, 2, 1, 1));
    }

    public function testRandFunctions()
    {
        $this->assertIsFloat(stats_rand_gen_beta(2.0, 3.0));
        $this->assertIsFloat(stats_rand_gen_chisquare(5));
        $this->assertIsFloat(stats_rand_gen_exponential(0.5));
        $this->assertIsFloat(stats_rand_gen_f(5, 10));
        $this->assertIsFloat(stats_rand_gen_funiform(5.0, 10.0));
        $this->assertIsFloat(stats_rand_gen_gamma(2.0, 3.0));
        $this->assertIsInt(stats_rand_gen_ibinomial(10, 0.5));
        $this->assertIsInt(stats_rand_gen_ibinomial_negative(5, 0.4));
        $this->assertIsInt(stats_rand_gen_int());
        $this->assertIsInt(stats_rand_gen_ipoisson(4.5));
        $this->assertIsInt(stats_rand_gen_iuniform(10, 50));
        $this->assertIsFloat(stats_rand_gen_noncentral_chisquare(5, 3.0));
        $this->assertIsFloat(stats_rand_gen_noncentral_f(5, 10, 3.0));
        $this->assertIsFloat(stats_rand_gen_noncentral_t(10, 3.0));
        $this->assertIsFloat(stats_rand_gen_normal(0, 1));
        $this->assertIsFloat(stats_rand_gen_t(10));
        $this->assertIsArray(stats_rand_get_seeds());

        $phrase = "Minha frase aleatória para gerar seeds";
        $seeds = stats_rand_phrase_to_seeds($phrase);
        $this->assertEquals(1972993212857857968, $seeds[0]);
        $this->assertEquals(6890407606759230023, $seeds[1]);

        $this->assertIsFloat(stats_rand_ranf());
        
        stats_rand_setall(123456789, 987654321);
        $this->assertEquals(117688751, mt_rand());
    }

    function testDensityFunctions()
    {
        $this->assertEquals(1.499999999999999, stats_dens_beta(0.5, 2, 3));
        $this->assertEquals(0.15915494309189535, stats_dens_cauchy(1.0, 0.0, 1.0));
        $this->assertEquals(0.18393972058572114, stats_dens_chisquare(2.0, 4));
        $this->assertEquals(0.07468060255179591, stats_dens_exponential(2.0, 1.5));
        $this->assertEquals(0.0935947544496856, stats_dens_f(2.5, 5, 10));
        $this->assertEquals(0.09196986029286054, stats_dens_gamma(2.0, 3.0, 2.0));
        $this->assertEquals(0.18393972058572117, stats_dens_laplace(1.0, 0.0, 1.0));
        $this->assertEquals(0.19661193324148188, stats_dens_logistic(1.0, 0.0, 1.0));
        $this->assertEquals(0.24197072451914337, stats_dens_normal(1.0, 0.0, 1.0));
        $this->assertEquals(0.3456, stats_dens_pmf_binomial(3, 5, 0.6));
        $this->assertEquals(0.20983971757065453, stats_dens_pmf_hypergeometric(2, 50, 10, 5));
        $this->assertEquals(0.09216000000000002, stats_dens_pmf_negative_binomial(3, 2, 0.6));
        $this->assertEquals(0.13360188578108528, stats_dens_pmf_poisson(4, 2.5));
        $this->assertEquals(0.23036198922913884, stats_dens_t(1.0, 10));
        $this->assertEquals(0.25, stats_dens_uniform(4.0, 2.0, 6.0));
        $this->assertEquals(0.2368778222828562, stats_dens_weibull(2.0, 1.5, 3.0));
    }

    function testStatFunctions()
    {
        $this->assertEquals(10, stats_stat_binomial_coef(3, 5));
        $this->assertEquals(0.7977240352174656, stats_stat_correlation([1, 2, 3, 4, 5], [1, 2, 3, 8, 5]));
        $this->assertEquals(120, stats_stat_factorial(5));
        $this->assertIsFloat(stats_stat_independent_t(
            [2.5, 3.0, 2.8, 3.2, 2.7], 
            [3.8, 3.7, 4.0, 3.6, 3.9]
        ));
        $this->assertEquals(32, stats_stat_innerproduct([1, 2, 3], [4, 5, 6]));
        $this->assertEquals(8.94427190999916, stats_stat_paired_t(
            [85, 90, 78, 92, 88], 
            [80, 85, 75, 89, 84]
        ));
        $this->assertEquals(6547, stats_stat_percentile([3, 4, 5, 4645, 32, 6547], 90));
        $this->assertEquals(30, stats_stat_powersum([1, 2, 3, 4], 2));
    }
}
