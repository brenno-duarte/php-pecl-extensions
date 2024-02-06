<?php

namespace PeclPolyfill\HRTime;

class StopWatch extends PerformanceCounter
{
    /**
     * @var array
     */
    private array $event = [];
    
    /**
     * @var int
     */
    private int $start;
    
    /**
     * @var int
     */
    private int $stop;
    
    /**
     * @var array
     */
    private array $interval = [];

    /**
     * Start time measurement
     * 
     * @return void
     */
    public function start(): void
    {
        $this->event['start'] = true;
        $this->start = hrtime(true);
    }

    /**
     * Stop time measurement
     * 
     * @return void
     */
    public function stop(): void
    {
        $this->event['stop'] = true;
        $this->stop = hrtime(true);
    }

    /**
     * Return if stopwatch is running
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        if (array_key_exists('start', $this->event)) {
            if ($this->event['start'] == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get elapsed time for the last interval
     *
     * @param int $unit
     * 
     * @return float 
     */
    public function getLastElapsedTime(int $unit): float
    {
        $this->interval[] = $this->stop - $this->start;
        $time = end($this->interval);

        switch ($unit) {
            case 0:
                return round($time / 1.e9);
                break;

            case 1:
                return $time / 1e+6;
                break;

            case 2:
                return round($time / 1.e6);
                break;

            case 3:
                return $time;
                break;
        }
    }

    /**
     * Get elapsed time for all intervals
     *
     * @param int $unit
     * 
     * @return float
     */
    public function getElapsedTime(int $unit): float
    {
        $time = array_sum($this->interval);

        switch ($unit) {
            case 0:
                return round($time / 1.e9);
                break;

            case 1:
                return $time / 1e+6;
                break;

            case 2:
                return round($time / 1.e6);
                break;

            case 3:
                return $time;
                break;
        }
    }

    /**
     * Get elapsed ticks for all intervals
     *
     * @return int
     */
    /* public function getElapsedTicks(): int
    {
    } */

    /**
     * Get elapsed ticks for the last interval
     *
     * @return int
     */
    /*  public function getLastElapsedTicks(): int
    {
    } */
}
