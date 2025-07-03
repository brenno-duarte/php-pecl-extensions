<?php

if (!extension_loaded('stats')) {
    function stats_absolute_deviation(array $a): float
    {
        if (empty($a)) {
            trigger_error("Dataset cannot be empty", E_USER_WARNING);
        }
    
        $meanValue = mean($a);
        $sumAbsoluteDifferences = 0;
    
        foreach ($a as $value) {
            $sumAbsoluteDifferences += abs($value - $meanValue);
        }
    
        return $sumAbsoluteDifferences / count($a);
    }

    /**
     * Calculates any one parameter of the beta distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which The flag to determine what to be calculated
     * 
     * @return float Returns CDF, x, alpha, or beta, determined by which
     */
    function stats_cdf_beta(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF (par1) dado x, alpha e beta
            return beta_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, alpha e beta (inverso da CDF, pode envolver métodos numéricos)
            return inverse_beta_cdf($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula alpha dado x, CDF e beta
            return find_alpha($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula beta dado x, CDF e alpha
            return find_beta($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the binomial distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which The flag to determine what to be calculated
     * 
     * @return float Returns CDF, x, n, or p, determined by which
     */
    function stats_cdf_binomial(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF para a distribuição binomial
            return binomial_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado o CDF, n e p
            return find_binomial_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula n dado x, CDF e p
            return find_binomial_n($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula p dado x, CDF e n
            return find_binomial_p($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Cauchy distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which The flag to determine what to be calculated
     * 
     * @return float Returns CDF, x, x0, or gamma, determined by which
     */
    function stats_cdf_cauchy(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, x0 e gamma
            return cauchy_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, x0 e gamma
            return inverse_cauchy_cdf($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula x0 dado x, CDF e gamma
            return find_cauchy_x0($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula gamma dado x, CDF e x0
            return find_cauchy_gamma($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the chi-square distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_chisquare(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x (par1) e k (par2)
            return chisquare_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado CDF (par1) e k (par2)
            return find_chisquare_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula k dado x (par1) e CDF (par2)
            return find_chisquare_k($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the exponential distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_exponential(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x (par1) e lambda (par2)
            return exponential_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado CDF (par1) e lambda (par2)
            return find_exponential_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula lambda dado x (par1) e CDF (par2)
            return find_exponential_lambda($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the F distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_f(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF para a distribuição F
            return f_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado o CDF e graus de liberdade (d1 e d2)
            return find_f_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula d1 dado x, CDF e d2
            return find_f_d1($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula d2 dado x, CDF e d1
            return find_f_d2($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    function stats_cdf_gamma(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, k e theta
            return gamma_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, k e theta
            return find_gamma_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula k dado x, CDF e theta
            return find_gamma_k($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula theta dado x, CDF e k
            return find_gamma_theta($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Laplace distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_laplace(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, mu e b
            return laplace_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, mu e b
            return find_laplace_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula mu dado x, CDF e b
            return find_laplace_mu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula b dado x, CDF e mu
            return find_laplace_b($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the logistic distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_logistic(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, mu e s
            return logistic_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, mu e s
            return find_logistic_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula mu dado x, CDF e s
            return find_logistic_mu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula s dado x, CDF e mu
            return find_logistic_s($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    function stats_cdf_negative_binomial(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, r e p
            return negative_binomial_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado CDF, r e p
            return find_negative_binomial_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula r dado x, CDF e p
            return find_negative_binomial_r($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula p dado x, CDF e r
            return find_negative_binomial_p($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the non-central chi-square distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_noncentral_chisquare(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, k e lambda
            return noncentral_chisquare_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado CDF, k e lambda
            return find_noncentral_chisquare_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula k dado x, CDF e lambda
            return find_noncentral_chisquare_k($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula lambda dado x, CDF e k
            return find_noncentral_chisquare_lambda($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the non-central F distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param float $par4
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_noncentral_f(float $par1, float $par2, float $par3, float $par4, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, nu1, nu2 e lambda
            return noncentral_f_cdf($par1, $par2, $par3, $par4);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, nu1, nu2 e lambda
            return find_noncentral_f_x($par1, $par2, $par3, $par4);
        } elseif ($which == 3) {
            // Calcula nu1 dado x, CDF, nu2 e lambda
            return find_noncentral_f_nu1($par1, $par2, $par3, $par4);
        } elseif ($which == 4) {
            // Calcula nu2 dado x, CDF, nu1 e lambda
            return find_noncentral_f_nu2($par1, $par2, $par3, $par4);
        } elseif ($which == 5) {
            // Calcula lambda dado x, CDF, nu1 e nu2
            return find_noncentral_f_lambda($par1, $par2, $par3, $par4);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the non-central t-distribution give values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_noncentral_t(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, nu e mu
            return noncentral_t_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado CDF, nu e mu
            return find_noncentral_t_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula nu dado x, CDF e mu
            return find_noncentral_t_nu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula mu dado x, CDF e nu
            return find_noncentral_t_mu($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the normal distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_normal(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, mu e sigma
            return normal_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, mu e sigma
            return find_normal_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula mu dado x, CDF e sigma
            return find_normal_mu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula sigma dado x, CDF e mu
            return find_normal_sigma($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Poisson distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_poisson(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x e lambda
            return poisson_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado a CDF e lambda
            return find_poisson_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula lambda dado x e CDF
            return find_poisson_lambda($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the t-distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_t(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x e graus de liberdade (nu)
            return t_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado a CDF e graus de liberdade (nu)
            return find_t_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula graus de liberdade (nu) dado x e CDF
            return find_t_nu($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the uniform distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_uniform(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, a e b
            return uniform_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, a e b
            return find_uniform_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula a dado x, CDF e b
            return find_uniform_a($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula b dado x, CDF e a
            return find_uniform_b($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Weibull distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    function stats_cdf_weibull(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, k e lambda
            return weibull_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, k e lambda
            return find_weibull_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula k dado x, CDF e lambda
            return find_weibull_k($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula lambda dado x, CDF e k
            return find_weibull_lambda($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Returns the covariance of a and b, or FALSE on failure.
     *
     * @param array $valuesA
     * @param array $valuesB
     *
     * @return float
     */
    function stats_covariance(array $valuesA, array $valuesB): float
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
        }  // for

        return $add / floatval($countA);
    }

    /**
     * Probability density function of the beta distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $a The shape parameter of the distribution
     * @param float $b The shape parameter of the distribution
     *
     * @return float
     */
    function stats_dens_beta(float $x, float $a, float $b): float
    {
        if ($x <= 0 || $x >= 1) {
            return 0;  // A densidade fora do intervalo (0, 1) é zero
        }

        $numerator = pow($x, $a - 1) * pow(1 - $x, $b - 1);
        $denominator = beta($a, $b);
        return $numerator / $denominator;
    }

    /**
     * Probability density function of the Cauchy distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The location parameter of the distribution
     * @param float $stdev The scale parameter of the distribution
     *
     * @return float
     */
    function stats_dens_cauchy(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev must be greater than 0', E_USER_WARNING);
        }

        $numerator = 1;
        $denominator = M_PI * $stdev * (1 + pow(($x - $ave) / $stdev, 2));

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the chi-square distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr The degree of freedom of the distribution
     *
     * @return float
     */
    function stats_dens_chisquare(float $x, float $dfr): float
    {
        if ($x < 0) {
            return 0;  // Para valores negativos, a densidade é 0
        }

        $k = $dfr;
        $numerator = pow($x, ($k / 2) - 1) * exp(-$x / 2);
        $denominator = pow(2, $k / 2) * gamma($k / 2);

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the exponential distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $scale The scale of the distribution
     *
     * @return float
     */
    function stats_dens_exponential(float $x, float $scale): float
    {
        if ($scale <= 0) {
            trigger_error('Param $scale (λ) must be greater than 0', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // Para valores negativos de x, a densidade é 0
        }

        return $scale * exp(-$scale * $x);
    }

    /**
     * Probability density function of the F distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr1 The degree of freedom of the distribution
     * @param float $dfr2 The degree of freedom of the distribution
     *
     * @return float
     */
    function stats_dens_f(float $x, float $dfr1, float $dfr2): float
    {
        if ($x <= 0) {
            return 0;  // A densidade para x <= 0 é zero
        }

        $numerator = sqrt(pow(
            $dfr1 * $x,
            $dfr1
        ) * pow(
            $dfr2,
            $dfr2
        ) / pow(
            $dfr1 * $x + $dfr2,
            $dfr1 + $dfr2
        ));

        $denominator = $x * beta($dfr1 / 2, $dfr2 / 2);
        return $numerator / $denominator;
    }

    /**
     * Probability density function of the gamma distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $shape The shape parameter of the distribution
     * @param float $scale The scale parameter of the distribution
     *
     * @return float
     */
    function stats_dens_gamma(float $x, float $shape, float $scale): float
    {
        if ($x < 0) {
            return 0;  // Para valores negativos de x, a densidade é 0
        }

        $numerator = pow($x, $shape - 1) * exp(-$x / $scale);
        $denominator = pow($scale, $shape) * gamma($shape);

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the Laplace distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The location parameter of the distribution
     * @param float $stdev The shape parameter of the distribution
     *
     * @return float
     */
    function stats_dens_laplace(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev (b) must be greater than 0', E_USER_WARNING);
        }

        $numerator = exp(-abs($x - $ave) / $stdev);
        $denominator = 2 * $stdev;

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the logistic distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The location parameter of the distribution
     * @param float $stdev The shape parameter of the distribution
     *
     * @return float
     */
    function stats_dens_logistic(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev (b) must be greater than 0', E_USER_WARNING);
        }

        $expTerm = exp(-($x - $ave) / $stdev);
        $denominator = $stdev * pow(1 + $expTerm, 2);

        return $expTerm / $denominator;
    }

    /**
     * Probability density function of the normal distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The mean of the distribution
     * @param float $stdev The standard deviation of the distribution
     *
     * @return float
     */
    function stats_dens_normal(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev (b) must be greater than 0', E_USER_WARNING);
        }

        $coefficient = 1 / ($stdev * sqrt(2 * M_PI));
        $exponent = exp(-pow($x - $ave, 2) / (2 * pow($stdev, 2)));

        return $coefficient * $exponent;
    }

    /**
     * Probability mass function of the binomial distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $n The number of trials of the distribution
     * @param float $pi The success rate of the distribution
     *
     * @return float
     */
    function stats_dens_pmf_binomial(float $x, float $n, float $pi): float
    {
        if ($pi < 0 || $pi > 1) {
            trigger_error('The probability (pi) must be between 0 and 1', E_USER_WARNING);
        }

        if ($x < 0 || $x > $n) {
            return 0;  // Fora do intervalo válido, a probabilidade é zero
        }

        $binomialCoeff = binomialCoefficient($n, $x);
        return $binomialCoeff * pow($pi, $x) * pow(1 - $pi, $n - $x);
    }

    /**
     * Probability mass function of the hypergeometric distribution
     *
     * @param float $n1 The number of success, at which the probability mass is calculated
     * @param float $n2 The number of failure of the distribution
     * @param float $N1 The number of success samples of the distribution
     * @param float $N2 The number of failure samples of the distribution
     *
     * @return float
     */
    function stats_dens_pmf_hypergeometric(float $n1, float $n2, float $N1, float $N2): float
    {
        if ($n1 < 0 || $n1 > $N1 || $n1 > $N2 || $N2 > $n2) {
            return 0;  // Valores inválidos resultam em probabilidade zero
        }

        $numerator = binomialCoefficient($N1, $n1) * binomialCoefficient($n2 - $N1, $N2 - $n1);
        $denominator = binomialCoefficient($n2, $N2);

        return $numerator / $denominator;
    }

    /**
     * Probability mass function of the negative binomial distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $n The number of the success of the distribution
     * @param float $pi The success rate of the distribution
     *
     * @return float
     */
    function stats_dens_pmf_negative_binomial(float $x, float $n, float $pi): float
    {
        if ($pi < 0 || $pi > 1) {
            trigger_error('The probability (pi) must be between 0 and 1', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // Probabilidade é zero para valores negativos de k
        }

        $binomialCoeff = binomialCoefficient($x + $n - 1, $x);
        return $binomialCoeff * pow($pi, $n) * pow(1 - $pi, $x);
    }

    /**
     * Probability mass function of the Poisson distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $lb The parameter of the Poisson distribution
     *
     * @return float
     */
    function stats_dens_pmf_poisson(float $x, float $lb): float
    {
        if ($lb <= 0) {
            trigger_error('The lambda param (λ) must be greater than 0', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // A probabilidade é zero para valores negativos de x
        }

        return (exp(-$lb) * pow($lb, $x)) / stats_stat_factorial($x);
    }

    /**
     * Probability density function of the t-distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr The degree of freedom of the distribution
     *
     * @return float
     */
    function stats_dens_t(float $x, float $dfr): float
    {
        if ($dfr <= 0) {
            trigger_error('The degree of freedom (dfr) must be greater than 0', E_USER_WARNING);
        }

        $numerator = gamma(($dfr + 1) / 2);
        $denominator = sqrt($dfr * M_PI) * gamma($dfr / 2);
        $coefficient = $numerator / $denominator;

        $term = pow(1 + ($x * $x) / $dfr, -($dfr + 1) / 2);

        return $coefficient * $term;
    }

    /**
     * Probability density function of the uniform distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $a The lower bound of the distribution
     * @param float $b The upper bound of the distribution
     *
     * @return float
     */
    function stats_dens_uniform(float $x, float $a, float $b): float
    {
        if ($a >= $b) {
            trigger_error('The lower limit (a) must be less than the upper limit (b)', E_USER_WARNING);
        }

        if ($x < $a || $x > $b) {
            return 0;  // Fora do intervalo [a, b], a densidade é 0
        }

        return 1 / ($b - $a);  // Densidade uniforme dentro do intervalo
    }

    /**
     * Probability density function of the Weibull distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $a The shape parameter of the distribution
     * @param float $b The scale parameter of the distribution
     *
     * @return float
     */
    function stats_dens_weibull(float $x, float $a, float $b): float
    {
        if ($a <= 0 || $b <= 0) {
            trigger_error('The shape (a) and scale (b) parameters must be greater than 0', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // A densidade é 0 para valores negativos de x
        }

        $term1 = ($a / $b);
        $term2 = pow($x / $b, $a - 1);
        $term3 = exp(-pow($x / $b, $a));

        return $term1 * $term2 * $term3;
    }

    /**
     * Returns the harmonic mean of the values in a, or FALSE if a is empty or is not an array
     *
     * @param mixed ...$a
     *
     * @return float|int
     */
    function stats_harmonic_mean(): float|int
    {
        $sum = null;
        $num_args = func_num_args();

        for ($i = 0; $i < $num_args; $i++) {
            $sum += 1 / func_get_arg($i);
        }

        return $num_args / $sum;
    }

    /**
     * Returns the kurtosis of the values in a, or FALSE if a is empty or is not an array.
     *
     * @param array $values
     *
     * @return bool
     */
    function stats_kurtosis(array $values): bool
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
        }  // foreach values

        $variance = $add2 / floatval($numValues);
        return ($add4 * $numValues) / ($add2 * $add2) - 3.0;
    }

    /**
     * Generates a random deviate from the beta distribution
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     */
    function stats_rand_gen_beta(float $a, float $b): float
    {
        if ($a <= 0 || $b <= 0) {
            trigger_error("Parameters a and b must be greater than 0", E_USER_WARNING);
        }
    
        $y1 = gammaRandom($a);
        $y2 = gammaRandom($b);
    
        return $y1 / ($y1 + $y2); // Transformação para Beta
    }

    /**
     * Generates a random deviate from the chi-square distribution
     *
     * @param float $df The degrees of freedom
     * 
     * @return float
     */
    function stats_rand_gen_chisquare(float $df): float
    {
        if ($df <= 0) {
            trigger_error("Degrees of freedom (df) must be greater than 0", E_USER_WARNING);
        }
    
        $sum = 0;

        for ($i = 0; $i < $df; $i++) {
            $z = randNormal(); // Variável normal padrão
            $sum += $z * $z; // Soma dos quadrados
        }
    
        return $sum;
    }

    /**
     * Generates a random deviate from the exponential distribution
     *
     * @param float $av The scale parameter
     * 
     * @return float
     */
    function stats_rand_gen_exponential(float $av): float {
        if ($av <= 0) {
            throw new InvalidArgumentException("Param \$av must be greater than 0");
        }
    
        $u = mt_rand() / mt_getrandmax(); // Número aleatório U(0, 1)
        return -log($u) / $av; // Transformação para a distribuição exponencial
    }

    /**
     * Generates a random deviate from the F distribution
     *
     * @param float $dfn The degrees of freedom in the numerator
     * @param float $dfd The degrees of freedom in the denominator
     * 
     * @return float
     */
    function stats_rand_gen_f(float $dfn, float $dfd): float
    {
        if ($dfn <= 0 || $dfd <= 0) {
            trigger_error("The degrees of freedom of the numerator and denominator must be greater than 0", E_USER_WARNING);
        }
    
        $chiSquareNumerator = stats_rand_gen_chisquare($dfn); // Qui-quadrado para o numerador
        $chiSquareDenominator = stats_rand_gen_chisquare($dfd); // Qui-quadrado para o denominador
    
        return ($chiSquareNumerator / $dfn) / ($chiSquareDenominator / $dfd); // Razão F
    }

    /**
     * Generates uniform float between low (exclusive) and high (exclusive)
     *
     * @param float $low The lower bound (inclusive)
     * @param float $high The upper bound (exclusive)
     * 
     * @return float
     */
    function stats_rand_gen_funiform(float $low, float $high): float
    {
        if ($low >= $high) {
            trigger_error("The lower limit (low) must be less than the upper limit (high)", E_USER_WARNING);
        }
    
        $random = mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1
        return $low + $random * ($high - $low); // Escala para o intervalo
    }

    /**
     * Generates a random deviate from the gamma distribution
     *
     * @param float $a location parameter of Gamma distribution (a > 0)
     * @param float $r shape parameter of Gamma distribution (r > 0)
     * 
     * @return float
     */
    function stats_rand_gen_gamma(float $a, float $r): float
    {
        if ($a <= 0 || $r <= 0) {
            trigger_error("The parameters \$a (scale) and \$r (shape) must be greater than 0", E_USER_WARNING);
        }
    
        if ($r < 1) {
            // Transformação para r < 1
            $c = 1 + $r * exp(-1);
            while (true) {
                $u = mt_rand() / mt_getrandmax();
                $v = mt_rand() / mt_getrandmax();
    
                $x = $c * $u;
                if ($x <= 1) {
                    $z = pow($x, 1 / $r);
                    if ($v <= exp(-$z)) {
                        return $z / $a;
                    }
                } else {
                    $z = -log(($c - $x) / $r);
                    if ($v <= pow($z, $r - 1)) {
                        return $z / $a;
                    }
                }
            }
        } else {
            // Método de Marsaglia-Tsang para r >= 1
            $d = $r - 1 / 3;
            $c = 1 / sqrt(9 * $d);
    
            while (true) {
                do {
                    $x = mt_rand() / mt_getrandmax() * 2 - 1;
                    $v = 1 + $c * $x;
                } while ($v <= 0);
    
                $v = pow($v, 3);
                $u = mt_rand() / mt_getrandmax();
    
                if ($u < 1 - 0.0331 * pow($x, 4)) {
                    return $d * $v / $a;
                }
    
                if ($u < exp(0.5 * pow($x, 2) + $d * (1 - $v + log($v)))) {
                    return $d * $v / $a;
                }
            }
        }
    }

    /**
     * Generates a random deviate from the binomial distribution
     *
     * @param int $n The number of trials
     * @param float $pp The probability of an event in each trial
     * 
     * @return int
     */
    function stats_rand_gen_ibinomial(int $n, float $pp): int
    {
        if ($n <= 0 || $pp < 0 || $pp > 1) {
            trigger_error("The number of trials (n) must be greater than 0 and the probability (p) must be between 0 and 1", E_USER_WARNING);
        }
    
        $successes = 0;
    
        for ($i = 0; $i < $n; $i++) {
            $random = mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1

            if ($random < $pp) {
                $successes++; // Incrementa o número de sucessos se o valor aleatório for menor que p
            }
        }
    
        return $successes;
    }

    /**
     * Generates a random deviate from the negative binomial distribution
     *
     * @param int $n The number of success
     * @param float $p The success rate
     * 
     * @return int
     */
    function stats_rand_gen_ibinomial_negative(int $n, float $p): int
    {
        if ($n <= 0 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException("O número de sucessos (n) deve ser maior que 0 e a probabilidade (p) deve estar no intervalo (0, 1].");
        }
    
        $failures = 0;
    
        for ($i = 0; $i < $n; $i++) {
            $failures += randGeometric($p) - 1; // Soma as falhas antes de cada sucesso
        }
    
        return $failures;
    }

    /**
     * Generates random integer between 1 and 2147483562
     *
     * @return int
     */
    function stats_rand_gen_int(): int
    {
        return mt_rand(1, 2147483562);
    }

    /**
     * Generates a single random deviate from a Poisson distribution
     *
     * @param float $mu The parameter of the Poisson distribution
     * 
     * @return int
     */
    function stats_rand_gen_ipoisson(float $mu): int
    {
        if ($mu <= 0) {
            trigger_error("Param \$mu must be greater than 0", E_USER_WARNING);
        }
    
        $L = exp(-$mu); // Limite
        $p = 1.0;
        $k = 0;
    
        do {
            $k++;
            $p *= mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1
        } while ($p > $L);
    
        return $k - 1;
    }

    /**
     * Generates integer uniformly distributed between LOW (inclusive) and HIGH (inclusive)
     *
     * @param int $low The lower bound
     * @param int $high The upper bound
     * 
     * @return int
     */
    function stats_rand_gen_iuniform(int $low, int $high): int
    {
        if ($low > $high) {
            trigger_error("The lower limit (low) must be less than or equal to the upper limit (high)", E_USER_WARNING);
        }
    
        return mt_rand($low, $high); // Gera um número inteiro aleatório entre low e high (inclusivo)
    }

    /**
     * Generates a random deviate from the non-central chi-square distribution
     *
     * @param float $df The degrees of freedom
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    function stats_rand_gen_noncentral_chisquare(float $df, float $xnonc): float
    {
        if ($df <= 0 || $xnonc < 0) {
            trigger_error("The degrees of freedom (df) must be greater than 0 and the non-centrality parameter (xnonc) must be greater than or equal to 0", E_USER_WARNING);
        }
    
        // Variável normal não central (com média sqrt(lambda) e variância 1)
        $nonCentralComponent = pow(randNormal() + sqrt($xnonc), 2);
    
        // Variável qui-quadrado com df-1 graus de liberdade
        $chiSquareComponent = stats_rand_gen_chisquare($df - 1);
    
        return $nonCentralComponent + $chiSquareComponent;
    }
    
    /**
     * Generates a random deviate from the noncentral F distribution
     *
     * @param float $dfn The degrees of freedom of the numerator
     * @param float $dfd The degrees of freedom of the denominator
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    function stats_rand_gen_noncentral_f(float $dfn, float $dfd, float $xnonc): float
    {
        if ($dfn <= 0 || $dfd <= 0 || $xnonc < 0) {
            trigger_error("The degrees of freedom (dfn, dfd) must be greater than 0 and the non-centrality parameter (xnonc) must be greater than or equal to 0", E_USER_WARNING);
        }
    
        // Gera valores aleatórios das distribuições qui-quadrado não centrais
        $numerator = stats_rand_gen_noncentral_chisquare($dfn, $xnonc); // Numerador
        $denominator = stats_rand_gen_chisquare($dfd); // Denominador
    
        // Calcula o valor F não central
        return ($numerator / $dfn) / ($denominator / $dfd);
    }

    /**
     * Generates a single random deviate from a non-central t-distribution
     *
     * @param float $df The degrees of freedom
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    function stats_rand_gen_noncentral_t(float $df, float $xnonc): float
    {
        if ($df <= 0 || $xnonc < 0) {
            trigger_error("The degrees of freedom (df) must be greater than 0 and the non-centrality parameter (xnonc) must be greater than or equal to 0", E_USER_WARNING);
        }
    
        // Componente normal, ajustado pelo parâmetro de não centralidade
        $numerator = randNormal() + $xnonc;
    
        // Componente qui-quadrado ajustado pelos graus de liberdade
        $denominator = sqrt(stats_rand_gen_chisquare($df) / $df);
    
        // Valor t não central
        return $numerator / $denominator;
    }

    /**
     * Generates a single random deviate from a normal distribution
     *
     * @param float $av The mean of the normal distribution
     * @param float $sd The standard deviation of the normal distribution
     * 
     * @return float
     */
    function stats_rand_gen_normal(float $av, float $sd): float
    {
        if ($sd <= 0) {
            throw new InvalidArgumentException("OThe standard deviation (\$sd) must be greater than 0");
        }

        $z0 = randNormal();
        return $z0 * $sd + $av;    
    }

    /**
     * Generates a single random deviate from a t-distribution
     *
     * @param float $df The degrees of freedom
     * 
     * @return float
     */
    function stats_rand_gen_t(float $df): float
    {
        if ($df <= 0) {
            trigger_error("Degrees of freedom (df) must be greater than 0", E_USER_WARNING);
        }
    
        // Numerador: variável normal padrão
        $numerator = randNormal();
    
        // Denominador: raiz quadrada da variável qui-quadrado dividida por df
        $denominator = sqrt(stats_rand_gen_chisquare($df) / $df);
    
        return $numerator / $denominator; // Valor da distribuição t
    }

    /**
     * Get the seed values of the random number generator
     *
     * @return array
     */
    function stats_rand_get_seeds(): array
    {
        // Simulamos as sementes geradas por mt_rand com base no tempo e em um número randômico
        $seed1 = mt_rand(1, 2147483647); // Gera uma semente aleatória dentro do intervalo
        $seed2 = mt_rand(1, 2147483647); // Gera uma segunda semente aleatória
    
        return [$seed1, $seed2];
    }

    /**
     * Generate two seeds for the RGN random number generator
     *
     * @param string $phrase The input phrase
     * 
     * @return array
     */
    function stats_rand_phrase_to_seeds(string $phrase): array
    {
        if (empty($phrase)) {
            throw new InvalidArgumentException("The phrase cannot be empty");
        }
    
        // Gera um hash MD5 da frase
        $hash = md5($phrase);
    
        // Divide o hash em duas partes e converte cada uma para um número inteiro
        $seed1 = hexdec(substr($hash, 0, 16)); // Primeiros 16 caracteres do hash
        $seed2 = hexdec(substr($hash, 16, 16)); // Últimos 16 caracteres do hash
    
        // Retorna as sementes como um array
        return [$seed1, $seed2];
    }

    function stats_rand_ranf(): float
    {
        // Gera um número aleatório no intervalo aberto (0, 1)
        // Garantimos que o valor não seja 0 substituindo números muito próximos de 0
        do {
            $random = mt_rand() / mt_getrandmax(); // Número aleatório entre 0 e 1
        } while ($random == 0); // Rejeita valores iguais a 0
    
        return $random;
    }

    /**
     * Set seed values to the random generator
     *
     * @param int $iseed1 The value which is used as the random seed
     * @param int $iseed2 The value which is used as the random seed
     * 
     * @return void
     */
    function stats_rand_setall(int $iseed1, int $iseed2): void
    {
        if ($iseed1 < 1 || $iseed1 > 2147483562 || $iseed2 < 1 || $iseed2 > 2147483398) {
            trigger_error("The values ​​of iseed1 and iseed2 must be in the ranges: 1 ≤ iseed1 ≤ 2147483562 and 1 ≤ iseed2 ≤ 2147483398", E_USER_WARNING);
        }
    
        // Combina as sementes de forma única para inicializar o gerador de números aleatórios
        $combinedSeed = ($iseed1 + $iseed2) % mt_getrandmax();
        mt_srand($combinedSeed);
    }

    /**
     * Returns the skewness of the values in a, or FALSE if a is empty or is not an array
     *
     * @param array $values
     *
     * @return bool|float
     */
    function stats_skew(array $values): bool|float
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
        }  // foreach values

        $variance = $add2 / floatval($numValues);

        return ($add3 / floatval($numValues)) / pow($variance, 3 / 2.0);
    }

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
    function stats_standard_deviation(array $a, bool $sample = false): float|bool
    {
        $n = count($a);
        if ($n === 0) {
            trigger_error('The array has zero elements', E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error('The array has only 1 element', E_USER_WARNING);
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

    /**
     * Returns a binomial coefficient
     *
     * @param int $x The number of chooses from the set
     * @param int $n The number of elements in the set
     *
     * @return float
     */
    function stats_stat_binomial_coef(int $x, int $n): float
    {
        if ($x > $n || $x < 0 || $n < 0) {
            trigger_error("The values \u{200B}\u{200B}of x and n must satisfy: 0 <= x <= n", E_USER_WARNING);
        }

        return stats_stat_factorial($n) / (stats_stat_factorial($x) * stats_stat_factorial($n - $x));
    }

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

    /**
     * Returns the factorial of an integer
     *
     * @param int $n An integer
     * 
     * @return float
     */
    function stats_stat_factorial(int $n): float
    {
        if ($n == 0 || $n == 1) {
            return 1;
        }

        return $n * stats_stat_factorial($n - 1);
    }

    /**
     * Returns the t-value from the independent two-sample t-test
     *
     * @param array $arr1 The first set of values
     * @param array $arr2 The second set of values
     * 
     * @return float
     */
    function stats_stat_independent_t(array $arr1, array $arr2): float
    {
        $n1 = count($arr1);
        $n2 = count($arr2);
    
        if ($n1 <= 1 || $n2 <= 1) {
            trigger_error("Each sample must have at least two elements", E_USER_WARNING);
        }
    
        $mean1 = mean($arr1);
        $mean2 = mean($arr2);
    
        $var1 = variance($arr1);
        $var2 = variance($arr2);
    
        // Calcula o denominador
        $denominator = sqrt(($var1 / $n1) + ($var2 / $n2));
    
        if ($denominator == 0) {
            trigger_error("The denominator is zero. Check the variances or sample sizes", E_USER_WARNING);
        }
    
        // Calcula o valor t
        $t = ($mean1 - $mean2) / $denominator;    
        return $t;
    }

    /**
     * Returns the inner product of two vectors
     *
     * @param array $arr1 The first array
     * @param array $arr2 The second array
     * 
     * @return float
     */
    function stats_stat_innerproduct(array $arr1, array $arr2): float
    {
        if (count($arr1) !== count($arr2)) {
            trigger_error("Arrays must be the same size", E_USER_WARNING);
        }
    
        $result = 0;

        for ($i = 0; $i < count($arr1); $i++) {
            $result += $arr1[$i] * $arr2[$i];
        }
    
        return $result;
    }

    /**
     * Returns the t-value of the dependent t-test for paired samples
     *
     * @param array $arr1 The first samples
     * @param array $arr2 The second samples
     * 
     * @return float
     */
    function stats_stat_paired_t(array $arr1, array $arr2): float
    {
        if (count($arr1) !== count($arr2)) {
            throw new InvalidArgumentException("Os dois conjuntos de dados devem ter o mesmo tamanho.");
        }
    
        $n = count($arr1);
        if ($n <= 1) {
            throw new InvalidArgumentException("O número de pares deve ser maior que 1.");
        }
    
        // Calculando as diferenças
        $differences = [];
        for ($i = 0; $i < $n; $i++) {
            $differences[] = $arr1[$i] - $arr2[$i];
        }
    
        // Calculando a média e o desvio padrão das diferenças
        $meanDifference = mean($differences);
        $stdDevDifference = standardDeviation($differences);
    
        // Calculando o valor t
        $t = $meanDifference / ($stdDevDifference / sqrt($n));
    
        return $t;
    }

    /**
     * Returns the percentile value
     *
     * @param array $arr
     * @param float $perc
     *
     * @return float
     */
    function stats_stat_percentile(array $arr, float $perc): float
    {
        $count = count($arr);
        sort($arr, SORT_NUMERIC);
        $low = floor(0.01 * $perc * $count);
        // $max = floor(0.01 * (100 - $perc) * $count);
        $percvar = $arr[$low];

        return $percvar;
    }

    /**
     * Returns the power sum of a vector
     *
     * @param array $arr The input array
     * @param float $power The power
     * 
     * @return float
     */
    function stats_stat_powersum(array $arr, float $power): float
    {
        $sum = 0.0;
    
        foreach ($arr as $value) {
            $sum += pow($value, $power);
        }
    
        return $sum;
    }

    /**
     * Returns the variance of the values in $data_set
     *
     * @param array $data_set
     *
     * @return float
     */
    function stats_variance(array $data_set)
    {
        $mean = array_sum($data_set) / count($data_set);

        $squared_sum = 0.0;
        foreach ($data_set as $data_point) {
            $deviation_from_mean = $data_point - $mean;
            $squared_sum += pow($deviation_from_mean, 2);
        }

        return $squared_sum / count($data_set);
    }

    /**
     * Generates a random deviate from the beta distribution
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     */
    /* function stats_rand_gen_beta(float $a, float $b): float
    {
        if ($a <= 1.0 && $b <= 1.0) {
            while (true) {
                $u = generate_random_double();
                $v = generate_random_double();
                $x = pow($u, 1.0 / $a);
                $y = pow($v, 1.0 / $b);

                if (($x + $y) <= 1.0) {
                    return $x / ($x + $y);
                }
            }
        } else {
            $gamma_a = generate_gamma_variate($a);
            $gamma_b = generate_gamma_variate($b);

            return $gamma_a / ($gamma_a + $gamma_b);
        }
    } */

    /**
     * @deprecated use gammaRandom
     */
    function generate_gamma_variate($shape)
    {
        if ($shape == 1.0) {
            return -log(1.0 - generate_random_double());
        } else if ($shape < 1.0) {
            while (true) {
                $u = generate_random_double();
                $v = -log(1.0 - generate_random_double());

                if ($u <= 1.0 - $shape) {
                    $x = pow($u, 1.0 / $shape);

                    if ($x <= $v) {
                        return $x;
                    }
                } else {
                    $y = -log((1.0 - $u) / $shape);
                    $x = pow(1.0 - $shape + ($shape * $y), 1.0 / $shape);

                    if ($x <= ($v + $y)) {
                        return $x;
                    }
                }
            }
        } else {
            $b = $shape - (1.0 / 3.0);
            $c = 1.0 / sqrt(9 * $b);
            while (true) {
                do {
                    $x = generate_random_gaussian_variate();
                    $v = 1.0 + ($c * $x);
                } while ($v <= 0.0);

                $v = pow($v, 3);
                $u = generate_random_double();

                if ($u < 1.0 - (0.0331 * pow($x, 4)) || (log($u) < (0.5 * pow($x, 2)) + ($b * (1.0 - $v + log($v))))) {
                    return $b * $v;
                }
            }
        }
    }

    /**
     * @deprecated use gammaRandom
     */
    function generate_random_double($min = 0.0, $max = 1.0)
    {
        $decimal = mt_rand() / mt_getrandmax();
        return $min + ($decimal * $max);
    }

    /**
     * @deprecated use gammaRandom
     */
    function generate_random_gaussian_variate()
    {
        do {
            $x1 = (2.0 * generate_random_double()) - 1.0;
            $x2 = (2.0 * generate_random_double()) - 1.0;
            $r2 = pow($x1, 2) + pow($x2, 2);
        } while ($r2 >= 1.0 || $r2 == 0.0);

        /* Box-Muller transform */
        $f = sqrt((-2.0 * log($r2)) / $r2);
        return $f * $x2;
    }

    function gamma($z)
    {
        // Aproximação de Stirling para a função gamma
        if ($z < 0.5) {
            return M_PI / (sin(M_PI * $z) * gamma(1 - $z));
        } else {
            $z -= 1;
            $x = 0.99999999999980993;
            $p = [
                676.5203681218851, -1259.1392167224028,
                771.32342877765313, -176.61502916214059,
                12.507343278686905, -0.13857109526572012,
                9.9843695780195716e-6, 1.5056327351493116e-7
            ];
            for ($i = 0; $i < count($p); $i++) {
                $x += $p[$i] / ($z + $i + 1);
            }
            $t = $z + count($p) - 0.5;
            return sqrt(2 * M_PI) * pow($t, $z + 0.5) * exp(-$t) * $x;
        }
    }

    function gammaRandom($shape) {
        if ($shape <= 0) {
            trigger_error("The shape parameter must be greater than 0", E_USER_WARNING);
        }
    
        // Método de Marsaglia-Tsang para gerar valores Gamma
        $d = $shape - 1.0 / 3.0;
        $c = 1.0 / sqrt(9.0 * $d);
    
        while (true) {
            $x = mt_rand() / mt_getrandmax() * 2.0 - 1.0; // Número aleatório U(-1, 1)
            $v = pow(1.0 + $c * $x, 3);
    
            if ($v > 0 && mt_rand() / mt_getrandmax() < exp(-0.5 * pow($x, 2) - $d * (1.0 - $v + log($v)))) {
                return $d * $v;
            }
        }
    }

    function beta($a, $b)
    {
        // Calcula a função beta usando a função gama
        return gamma($a) * gamma($b) / gamma($a + $b);
    }

    function binomialCoefficient($n, $x)
    {
        // Calcula o coeficiente binomial (n choose x)
        return stats_stat_factorial($n) / (stats_stat_factorial($x) * stats_stat_factorial($n - $x));
    }

    function mean($arr) {
        return array_sum($arr) / count($arr);
    }
    
    function variance($arr) {
        $mean = mean($arr);
        $sum = 0;
        foreach ($arr as $value) {
            $sum += pow($value - $mean, 2);
        }
        return $sum / (count($arr) - 1); // Variância amostral (dividido por n-1)
    }

    function standardDeviation($arr) {
        $mean = mean($arr);
        $sum = 0;
        foreach ($arr as $value) {
            $sum += pow($value - $mean, 2);
        }
        return sqrt($sum / (count($arr) - 1)); // Desvio padrão amostral
    }

    function randNormal() {
        // Gera um número aleatório a partir de uma distribuição normal padrão (média 0, desvio padrão 1)
        $u1 = mt_rand() / mt_getrandmax();
        $u2 = mt_rand() / mt_getrandmax();
    
        $z = sqrt(-2 * log($u1)) * cos(2 * M_PI * $u2); // Método Box-Muller
        return $z;
    }

    function randGeometric($p) {
        if ($p <= 0 || $p > 1) {
            trigger_error("The probability (p) must be in the interval (0, 1]", E_USER_WARNING);
        }
    
        $u = mt_rand() / mt_getrandmax(); // Número aleatório U(0, 1)
        return (int) ceil(log(1 - $u) / log(1 - $p)); // Transformação para distribuição geométrica
    }

    function beta_cdf($x, $alpha, $beta) {
        if ($x < 0 || $x > 1) {
            throw new InvalidArgumentException('x deve estar no intervalo [0, 1].');
        }
    
        $betaFunc = beta_function($alpha, $beta);
        $result = 0;
    
        // Integração numérica usando somas discretas
        $steps = 1000; // Aumente este valor para maior precisão
        $dx = $x / $steps;
    
        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, $alpha - 1) * pow(1 - $t, $beta - 1) / $betaFunc * $dx;
        }
    
        return $result;
    }
    
    function beta_function($alpha, $beta) {
        return gamma($alpha) * gamma($beta) / gamma($alpha + $beta);
    }

    function inverse_beta_cdf($cdf, $alpha, $beta) {
        if ($cdf < 0 || $cdf > 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo [0, 1].');
        }
    
        $low = 0;
        $high = 1;
        $tolerance = 1e-6; // Precisão do resultado
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = beta_cdf($mid, $alpha, $beta);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2; // Aproximação de x
    }

    function find_alpha($x, $cdf, $beta) {
        $alpha = 1; // Valor inicial
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = beta_cdf($x, $alpha, $beta);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $alpha; // Encontrei alpha
            }
    
            // Ajustar alpha com base na diferença
            if ($calculatedCDF < $cdf) {
                $alpha += 0.1;
            } else {
                $alpha -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar alpha dentro do número máximo de iterações.');
    }

    function find_beta($x, $cdf, $alpha) {
        $beta = 1; // Valor inicial
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = beta_cdf($x, $alpha, $beta);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $beta; // Encontrei beta
            }
    
            // Ajustar beta com base na diferença
            if ($calculatedCDF < $cdf) {
                $beta += 0.1;
            } else {
                $beta -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar beta dentro do número máximo de iterações.');
    }

    // Função para calcular a CDF da distribuição binomial
    function binomial_cdf($x, $n, $p) {
        $cdf = 0;
        for ($k = 0; $k <= $x; $k++) {
            $cdf += binomial_pmf($k, $n, $p);
        }
        return $cdf;
    }

    // Função para calcular o coeficiente binomial
    function binomial_coefficient($n, $k) {
        if ($k > $n) {
            return 0;
        }
        $result = 1;
        for ($i = 1; $i <= $k; $i++) {
            $result *= ($n - $i + 1) / $i;
        }
        return $result;
    }

    // Função para calcular a probabilidade de massa (PMF) binomial
    function binomial_pmf($x, $n, $p) {
        return binomial_coefficient($n, $x) * pow($p, $x) * pow(1 - $p, $n - $x);
    }

    // Função para encontrar x dado o CDF, n e p
    function find_binomial_x($cdf, $n, $p) {
        $x = 0;
        while ($x <= $n) {
            if (binomial_cdf($x, $n, $p) >= $cdf) {
                return $x;
            }
            $x++;
        }
        throw new RuntimeException('Não foi possível encontrar x.');
    }

    // Função para encontrar n dado x, CDF e p
    function find_binomial_n($x, $cdf, $p) {
        $n = 1;
        while ($n <= 1000) { // Limite arbitrário para evitar loop infinito
            if (binomial_cdf($x, $n, $p) >= $cdf) {
                return $n;
            }
            $n++;
        }
        throw new RuntimeException('Não foi possível encontrar n.');
    }

    // Função para encontrar p dado x, CDF e n
    function find_binomial_p($x, $cdf, $n) {
        $low = 0;
        $high = 1;
        $tolerance = 1e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            if (binomial_cdf($x, $n, $mid) < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
        return ($low + $high) / 2;
    }

    // Função para calcular a CDF da distribuição de Cauchy
    function cauchy_cdf($x, $x0, $gamma) {
        return 0.5 + atan(($x - $x0) / $gamma) / M_PI;
    }

    // Função para calcular o valor de x dado a CDF, x0 e gamma (inverso da CDF)
    function inverse_cauchy_cdf($cdf, $x0, $gamma) {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
        return $x0 + $gamma * tan(M_PI * ($cdf - 0.5));
    }

    // Função para encontrar x0 dado x, CDF e gamma
    function find_cauchy_x0($x, $cdf, $gamma) {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
        return $x - $gamma * tan(M_PI * ($cdf - 0.5));
    }

    // Função para encontrar gamma dado x, CDF e x0
    function find_cauchy_gamma($x, $cdf, $x0) {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
        return ($x - $x0) / tan(M_PI * ($cdf - 0.5));
    }

    function chisquare_cdf($x, $k) {
        if ($x < 0 || $k <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0 e k > 0.');
        }
    
        // Integração numérica para aproximação da CDF
        $gammaK = gamma($k / 2);
        $result = 0;
        $steps = 1000;
        $dx = $x / $steps;
    
        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, ($k / 2) - 1) * exp(-$t / 2) / ($gammaK * pow(2, $k / 2)) * $dx;
        }
    
        return $result;
    }
    
    // Função para encontrar x dado o CDF e k (busca numérica)
    function find_chisquare_x($cdf, $k) {
        if ($cdf < 0 || $cdf > 1 || $k <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e k > 0.');
        }
    
        $low = 0;
        $high = 100; // Define um limite superior arbitrário
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = chisquare_cdf($mid, $k);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }
    
    // Função para encontrar k dado x e CDF (busca iterativa)
    function find_chisquare_k($x, $cdf) {
        if ($x < 0 || $cdf < 0 || $cdf > 1) {
            throw new InvalidArgumentException('x deve ser >= 0 e CDF deve estar no intervalo [0, 1].');
        }
    
        $k = 1; // Valor inicial para k
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = chisquare_cdf($x, $k);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k; // Encontrou k
            }
    
            // Ajuste do valor de k
            if ($calculatedCDF < $cdf) {
                $k += 0.1;
            } else {
                $k -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }
    
    function exponential_cdf($x, $lambda) {
        if ($x < 0 || $lambda <= 0) {
            throw new InvalidArgumentException('x must be >= 0 and lambda > 0.');
        }
        return 1 - exp(-$lambda * $x);
    }
    
    function find_exponential_x($cdf, $lambda) {
        if ($cdf < 0 || $cdf > 1 || $lambda <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e lambda > 0.');
        }
        return -log(1 - $cdf) / $lambda;
    }
    
    function find_exponential_lambda($x, $cdf) {
        if ($x < 0 || $cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('x deve ser >= 0 e CDF deve estar no intervalo (0, 1).');
        }
        return -log(1 - $cdf) / $x;
    }

    function f_cdf($x, $d1, $d2) {
        if ($x < 0 || $d1 <= 0 || $d2 <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0 e d1, d2 > 0.');
        }
    
        // Usa a função beta incompleta regularizada para calcular a CDF
        $numerator = $d1 * $x;
        $denominator = $d1 * $x + $d2;
    
        return regularized_incomplete_beta($numerator / $denominator, $d1 / 2, $d2 / 2);
    }
    
    function find_f_x($cdf, $d1, $d2) {
        if ($cdf < 0 || $cdf > 1 || $d1 <= 0 || $d2 <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e d1, d2 > 0.');
        }
    
        $low = 0;
        $high = 100; // Limite arbitrário para o valor de x
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = f_cdf($mid, $d1, $d2);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }
    
    function find_f_d1($x, $cdf, $d2) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $d2 <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e d2 > 0.');
        }
    
        $d1 = 1; // Valor inicial para d1
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = f_cdf($x, $d1, $d2);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $d1; // Encontrou d1
            }
    
            // Ajustar d1 com base na diferença
            if ($calculatedCDF < $cdf) {
                $d1 += 0.1;
            } else {
                $d1 -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar d1 dentro do número máximo de iterações.');
    }
    
    function find_f_d2($x, $cdf, $d1) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $d1 <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e d1 > 0.');
        }
    
        $d2 = 1; // Valor inicial para d2
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = f_cdf($x, $d1, $d2);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $d2; // Encontrou d2
            }
    
            // Ajustar d2 com base na diferença
            if ($calculatedCDF < $cdf) {
                $d2 += 0.1;
            } else {
                $d2 -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar d2 dentro do número máximo de iterações.');
    }

    function gamma_cdf($x, $k, $theta) {
        if ($x < 0 || $k <= 0 || $theta <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, k > 0 e theta > 0.');
        }
    
        return regularized_incomplete_gamma($x / $theta, $k);
    }

    function regularized_incomplete_gamma($x, $a) {
        return incomplete_gamma($x, $a) / gamma($a);
    }
    
    // Função para calcular a função gama incompleta
    function incomplete_gamma($x, $a) {
        $steps = 1000; // Precisão da integração
        $dx = $x / $steps;
        $result = 0;
    
        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, $a - 1) * exp(-$t) * $dx;
        }
    
        return $result;
    }
    
    // Função para encontrar x dado a CDF, k e theta
    function find_gamma_x($cdf, $k, $theta) {
        if ($cdf < 0 || $cdf > 1 || $k <= 0 || $theta <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], k > 0 e theta > 0.');
        }
    
        $low = 0;
        $high = 100; // Limite arbitrário para x
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = gamma_cdf($mid, $k, $theta);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }
    
    // Função para encontrar k dado x, CDF e theta
    function find_gamma_k($x, $cdf, $theta) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $theta <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e theta > 0.');
        }
    
        $k = 1; // Valor inicial para k
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = gamma_cdf($x, $k, $theta);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;
            }
    
            // Ajuste do valor de k
            if ($calculatedCDF < $cdf) {
                $k += 0.1;
            } else {
                $k -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }
    
    // Função para encontrar theta dado x, CDF e k
    function find_gamma_theta($x, $cdf, $k) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $k <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e k > 0.');
        }
    
        $theta = 1; // Valor inicial para theta
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = gamma_cdf($x, $k, $theta);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $theta;
            }
    
            // Ajuste do valor de theta
            if ($calculatedCDF < $cdf) {
                $theta += 0.1;
            } else {
                $theta -= 0.1;
            }
        }
    
        throw new RuntimeException('Não foi possível encontrar theta dentro do número máximo de iterações.');
    }
    
    function incomplete_beta($x, $a, $b) {
        if ($x < 0 || $x > 1) {
            throw new InvalidArgumentException('x deve estar no intervalo [0, 1].');
        }
    
        $steps = 1000; // A precisão da integração pode ser ajustada com esse valor
        $dx = $x / $steps;
        $result = 0;
    
        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, $a - 1) * pow(1 - $t, $b - 1) * $dx;
        }
    
        return $result;
    }
    
    function regularized_incomplete_beta($x, $a, $b) {
        // Essa função pode ser implementada usando aproximações numéricas
        // Aqui está uma implementação simplificada
        return incomplete_beta($x, $a, $b) / beta_function($a, $b);
    }

    function laplace_cdf($x, $mu, $b) {
        if ($b <= 0) {
            throw new InvalidArgumentException('O parâmetro b deve ser > 0.');
        }
    
        if ($x < $mu) {
            return 0.5 * exp(($x - $mu) / $b);
        } else {
            return 1 - 0.5 * exp(-($x - $mu) / $b);
        }
    }
    
    function find_laplace_x($cdf, $mu, $b) {
        if ($cdf <= 0 || $cdf >= 1 || $b <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1) e b > 0.');
        }
    
        if ($cdf < 0.5) {
            return $mu + $b * log(2 * $cdf);
        } else {
            return $mu - $b * log(2 * (1 - $cdf));
        }
    }
    
    function find_laplace_mu($x, $cdf, $b) {
        if ($b <= 0 || $cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('b deve ser > 0 e CDF no intervalo (0, 1).');
        }
    
        if ($cdf < 0.5) {
            return $x - $b * log(2 * $cdf);
        } else {
            return $x + $b * log(2 * (1 - $cdf));
        }
    }
    
    function find_laplace_b($x, $cdf, $mu) {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1).');
        }
    
        if ($cdf < 0.5) {
            return ($x - $mu) / log(2 * $cdf);
        } else {
            return ($mu - $x) / log(2 * (1 - $cdf));
        }
    }

    function logistic_cdf($x, $mu, $s) {
        if ($s <= 0) {
            throw new InvalidArgumentException('O parâmetro s deve ser > 0.');
        }
    
        return 1 / (1 + exp(-($x - $mu) / $s));
    }
    
    function find_logistic_x($cdf, $mu, $s) {
        if ($cdf <= 0 || $cdf >= 1 || $s <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1) e s > 0.');
        }
    
        return $mu + $s * log($cdf / (1 - $cdf));
    }
    
    function find_logistic_mu($x, $cdf, $s) {
        if ($cdf <= 0 || $cdf >= 1 || $s <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1) e s > 0.');
        }
    
        return $x - $s * log($cdf / (1 - $cdf));
    }

    function find_logistic_s($x, $cdf, $mu) {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
    
        return ($x - $mu) / log($cdf / (1 - $cdf));
    }

    function negative_binomial_cdf($x, $r, $p) {
        if ($x < 0 || $r <= 0 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException('Os valores de x, r e p devem estar no intervalo correto.');
        }
    
        $cdf = 0;
        for ($k = 0; $k <= $x; $k++) {
            $cdf += negative_binomial_pmf($k, $r, $p);
        }
        return $cdf;
    }
    
    function negative_binomial_pmf($x, $r, $p) {
        return binomial_coefficient($x + $r - 1, $x) * pow($p, $r) * pow(1 - $p, $x);
    }
    
    function find_negative_binomial_x($cdf, $r, $p) {
        if ($cdf < 0 || $cdf > 1 || $r <= 0 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], r > 0 e 0 < p <= 1.');
        }
    
        $x = 0;
        while (negative_binomial_cdf($x, $r, $p) < $cdf) {
            $x++;
        }
        return $x;
    }
    
    function find_negative_binomial_r($x, $cdf, $p) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e 0 < p <= 1.');
        }
    
        $r = 1;
        $tolerance = 1e-6;
        while (true) {
            $calculatedCDF = negative_binomial_cdf($x, $r, $p);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $r;
            }
            $r += 0.1; // Ajuste iterativo
        }
    }
    
    function find_negative_binomial_p($x, $cdf, $r) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $r <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e r > 0.');
        }
    
        $low = 0;
        $high = 1;
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = negative_binomial_cdf($x, $r, $mid);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }

    function noncentral_chisquare_cdf($x, $k, $lambda) {
        if ($x < 0 || $k <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('Os valores devem atender: x >= 0, k > 0 e lambda >= 0.');
        }
    
        $sum = 0;
        $terms = 100; // Número de termos para aproximação
        for ($i = 0; $i < $terms; $i++) {
            $poissonTerm = exp(-$lambda / 2) * pow($lambda / 2, $i) / stats_stat_factorial($i);
            $chiSquareTerm = central_chisquare_cdf($x, $k + 2 * $i);
            $sum += $poissonTerm * $chiSquareTerm;
        }
    
        return $sum;
    }
    
    // Função para calcular a CDF da distribuição qui-quadrado central
    function central_chisquare_cdf($x, $k) {
        if ($x < 0 || $k <= 0) {
            throw new InvalidArgumentException('Os valores devem atender: x >= 0 e k > 0.');
        }
    
        return regularized_incomplete_gamma($x / 2, $k / 2);
    }
    
    function find_noncentral_chisquare_x($cdf, $k, $lambda) {
        if ($cdf < 0 || $cdf > 1 || $k <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], k > 0 e lambda >= 0.');
        }
    
        $low = 0;
        $high = 100;
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = noncentral_chisquare_cdf($mid, $k, $lambda);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }
    
    function find_noncentral_chisquare_k($x, $cdf, $lambda) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $lambda < 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo [0, 1] e lambda >= 0.');
        }
    
        $k = 1;
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = noncentral_chisquare_cdf($x, $k, $lambda);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;
            }
    
            $k += 0.1;
        }
    
        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }
    
    function find_noncentral_chisquare_lambda($x, $cdf, $k) {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $k <= 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo [0, 1] e k > 0.');
        }
    
        $lambda = 0;
        $tolerance = 1e-6;
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = noncentral_chisquare_cdf($x, $k, $lambda);
    
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $lambda;
            }
    
            $lambda += 0.1;
        }
    
        throw new RuntimeException('Não foi possível encontrar lambda dentro do número máximo de iterações.');
    }

    function noncentral_f_cdf($x, $nu1, $nu2, $lambda) {
        if ($x < 0 || $nu1 <= 0 || $nu2 <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('Os valores devem atender: x >= 0, nu1 > 0, nu2 > 0 e lambda >= 0.');
        }
    
        $sum = 0;
        $terms = 100; // Número de termos para aproximação
        for ($i = 0; $i < $terms; $i++) {
            $poissonTerm = exp(-$lambda / 2) * pow($lambda / 2, $i) / stats_stat_factorial($i);
            $fTerm = central_f_cdf($x, $nu1 + 2 * $i, $nu2);
            $sum += $poissonTerm * $fTerm;
        }
    
        return $sum;
    }
    
    function central_f_cdf($x, $nu1, $nu2) {
        $beta = beta_function($nu1 / 2, $nu2 / 2);
        $sum = 0;
        $terms = 1000;
        $dx = $x / $terms;
    
        for ($i = 0; $i <= $terms; $i++) {
            $t = $i * $dx;
            $sum += pow($t, $nu1 / 2 - 1) * pow(1 + ($nu1 * $t) / $nu2, -($nu1 + $nu2) / 2) / $beta * $dx;
        }
    
        return $sum;
    }
    
    function find_noncentral_f_x($cdf, $nu1, $nu2, $lambda) {
        if ($cdf < 0 || $cdf > 1 || $nu1 <= 0 || $nu2 <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], nu1, nu2 > 0 e lambda >= 0.');
        }
    
        $low = 0;
        $high = 100;
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = noncentral_f_cdf($mid, $nu1, $nu2, $lambda);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }
    
    function find_noncentral_f_nu1($x, $cdf, $nu2, $lambda) {
        $nu1 = 1;
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = noncentral_f_cdf($x, $nu1, $nu2, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu1;
            }
            $nu1 += 0.1;
        }
    }
    
    function find_noncentral_f_nu2($x, $cdf, $nu1, $lambda) {
        $nu2 = 1;
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = noncentral_f_cdf($x, $nu1, $nu2, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu2;
            }
            $nu2 += 0.1;
        }
    }
    
    function find_noncentral_f_lambda($x, $cdf, $nu1, $nu2) {
        $lambda = 0;
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = noncentral_f_cdf($x, $nu1, $nu2, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $lambda;
            }
            $lambda += 0.1;
        }
    }

    function noncentral_t_cdf($x, $nu, $mu) {
        if ($nu <= 0) {
            throw new InvalidArgumentException('O parâmetro nu deve ser > 0.');
        }
    
        $sum = 0;
        $terms = 100; // Número de termos para aproximar a soma
        for ($i = 0; $i < $terms; $i++) {
            $poissonTerm = exp(-$mu * $mu / 2) * pow($mu * $mu / 2, $i) / stats_stat_factorial($i);
            $tTerm = central_t_cdf($x, $nu + 2 * $i);
            $sum += $poissonTerm * $tTerm;
        }
    
        return $sum;
    }
    
    function central_t_cdf($x, $nu) {
        $beta = beta_function($nu / 2, 0.5);
        $integral = 0;
        $steps = 1000;
        $dx = $x / $steps;
    
        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $integral += pow(1 + $t * $t / $nu, -($nu + 1) / 2) / $beta * $dx;
        }
    
        return $integral;
    }
    
    function find_noncentral_t_x($cdf, $nu, $mu) {
        if ($cdf < 0 || $cdf > 1 || $nu <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo [0, 1] e nu > 0.');
        }
    
        $low = -100;
        $high = 100;
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = noncentral_t_cdf($mid, $nu, $mu);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }
    
    // Função para calcular nu dado x, CDF e mu
    function find_noncentral_t_nu($x, $cdf, $mu) {
        $nu = 1;
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = noncentral_t_cdf($x, $nu, $mu);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu;
            }
            $nu += 0.1;
        }
    }
    
    // Função para calcular mu dado x, CDF e nu
    function find_noncentral_t_mu($x, $cdf, $nu) {
        $mu = 0;
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = noncentral_t_cdf($x, $nu, $mu);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $mu;
            }
            $mu += 0.1;
        }
    }

    function normal_cdf($x, $mu, $sigma) {
        if ($sigma <= 0) {
            throw new InvalidArgumentException('O parâmetro sigma deve ser > 0.');
        }
    
        $z = ($x - $mu) / ($sigma * sqrt(2));
        return 0.5 * (1 + erf($z));
    }
    
    function erf($z) {
        // Aproximação numérica para a função de erro
        $t = 1 / (1 + 0.3275911 * abs($z));
        $tau = $t * (
            0.254829592 +
            $t * (-0.284496736 +
            $t * (1.421413741 +
            $t * (-1.453152027 +
            $t * 1.061405429)))
        );
    
        $erf = 1 - $tau * exp(-$z * $z);
        return $z >= 0 ? $erf : -$erf;
    }
    
    function find_normal_x($cdf, $mu, $sigma) {
        if ($cdf <= 0 || $cdf >= 1 || $sigma <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1) e sigma > 0.');
        }
    
        $z = sqrt(2) * inverse_erf(2 * $cdf - 1);
        return $mu + $z * $sigma;
    }
    
    function find_normal_mu($x, $cdf, $sigma) {
        if ($cdf <= 0 || $cdf >= 1 || $sigma <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1) e sigma > 0.');
        }
    
        $z = sqrt(2) * inverse_erf(2 * $cdf - 1);
        return $x - $z * $sigma;
    }
    
    function find_normal_sigma($x, $cdf, $mu) {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1).');
        }
    
        $z = sqrt(2) * inverse_erf(2 * $cdf - 1);
        return ($x - $mu) / $z;
    }
    
    function inverse_erf($z) {
        // Aproximação numérica para a função inversa de erro
        $a = 0.147; // Constante
        $sign = $z < 0 ? -1 : 1;
        $ln = log(1 - $z * $z);
    
        $inverse = $sign * sqrt(
            sqrt((2 / ($a * M_PI) + $ln / 2) ** 2 - $ln / $a) - (2 / ($a * M_PI) + $ln / 2)
        );
    
        return $inverse;
    }

    function poisson_cdf($x, $lambda) {
        if ($x < 0 || $lambda <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0 e lambda > 0.');
        }
    
        $sum = 0;
        for ($k = 0; $k <= $x; $k++) {
            $sum += poisson_pmf($k, $lambda);
        }
        return $sum;
    }
    
    function poisson_pmf($x, $lambda) {
        return exp(-$lambda) * pow($lambda, $x) / stats_stat_factorial($x);
    }
    
    function find_poisson_x($cdf, $lambda) {
        if ($cdf < 0 || $cdf > 1 || $lambda <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e lambda > 0.');
        }
    
        $x = 0;
        while (poisson_cdf($x, $lambda) < $cdf) {
            $x++;
        }
        return $x;
    }
    
    function find_poisson_lambda($x, $cdf) {
        if ($x < 0 || $cdf < 0 || $cdf > 1) {
            throw new InvalidArgumentException('x deve ser >= 0 e CDF no intervalo [0, 1].');
        }
    
        $lambda = 0.1; // Começa com um valor pequeno para lambda
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = poisson_cdf($x, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $lambda;
            }
            $lambda += 0.01;
        }
    }

    function t_cdf($x, $nu) {
        if ($nu <= 0) {
            throw new InvalidArgumentException('O parâmetro nu deve ser > 0.');
        }
    
        $beta = beta_function($nu / 2, 0.5);
        $integral = 0;
        $steps = 1000;
        $dx = $x / $steps;
    
        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $integral += pow(1 + $t * $t / $nu, -($nu + 1) / 2) / $beta * $dx;
        }
    
        return 0.5 + $integral;
    }
    
    // Função para calcular graus de liberdade (nu) dado x e CDF
    function find_t_nu($x, $cdf) {
        $nu = 1;
        $tolerance = 1e-6;
    
        while (true) {
            $calculatedCDF = t_cdf($x, $nu);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu;
            }
            $nu += 0.1; // Incrementa gradualmente para encontrar o valor
        }
    }
    
    // Função para calcular x dado a CDF e graus de liberdade (nu)
    function find_t_x($cdf, $nu) {
        if ($cdf < 0 || $cdf > 1 || $nu <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo [0, 1] e nu > 0.');
        }
    
        $low = -10;
        $high = 10;
        $tolerance = 1e-6;
    
        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = t_cdf($mid, $nu);
    
            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
    
        return ($low + $high) / 2;
    }

    function uniform_cdf($x, $a, $b) {
        if ($a >= $b) {
            throw new InvalidArgumentException('O parâmetro "a" deve ser menor que "b".');
        }
        if ($x < $a) {
            return 0.0;
        } elseif ($x > $b) {
            return 1.0;
        } else {
            return ($x - $a) / ($b - $a);
        }
    }
    
    function find_uniform_x($cdf, $a, $b) {
        if ($cdf < 0 || $cdf > 1 || $a >= $b) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e "a" < "b".');
        }
        return $a + $cdf * ($b - $a);
    }
    
    function find_uniform_a($x, $cdf, $b) {
        if ($cdf < 0 || $cdf > 1 || $x > $b) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e x <= b.');
        }
        return $x - $cdf * ($b - $x) / (1 - $cdf);
    }
    
    function find_uniform_b($x, $cdf, $a) {
        if ($cdf < 0 || $cdf > 1 || $x < $a) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e x >= a.');
        }
        return $x + ($x - $a) / $cdf;
    }

    function weibull_cdf($x, $k, $lambda) {
        if ($x < 0 || $k <= 0 || $lambda <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, k > 0 e lambda > 0.');
        }
    
        return 1 - exp(-pow($x / $lambda, $k));
    }
    
    function find_weibull_x($cdf, $k, $lambda) {
        if ($cdf <= 0 || $cdf >= 1 || $k <= 0 || $lambda <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1), k > 0 e lambda > 0.');
        }
    
        return $lambda * pow(-log(1 - $cdf), 1 / $k);
    }
    
    function find_weibull_k($x, $cdf, $lambda) {
        if ($x < 0 || $cdf <= 0 || $cdf >= 1 || $lambda <= 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo (0, 1) e lambda > 0.');
        }
    
        $tolerance = 1e-6;
        $k = 1; // Inicializa com um valor razoável
        $maxIterations = 1000;
    
        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = weibull_cdf($x, $k, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;
            }
            $k += 0.1;
        }
    
        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }
    
    function find_weibull_lambda($x, $cdf, $k) {
        if ($x < 0 || $cdf <= 0 || $cdf >= 1 || $k <= 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo (0, 1) e k > 0.');
        }
    
        return $x / pow(-log(1 - $cdf), 1 / $k);
    }
}
