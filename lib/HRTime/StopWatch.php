<?php

namespace PeclPolyfill\HRTime;

class StopWatch
{
    private array $event = [];
    private int $start;
    private int $stop;
    private array $interval = [];

    /**
     * Start a timer
     */
    public function start(): void
    {
        $this->event['start'] = true;
        $this->start = hrtime(true);
    }

    /**
     * Stop a timer
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
     * 
     */
    public function getLastElapsedTime(int $unit)
    {
        $this->interval[] = $this->stop - $this->start;
        $time = end($this->interval);

        switch ($unit) {
            case 0:
                return date('s', $time);
                break;
            
            case 1:
                return date('u', $time);
                break;

            case 2:
                return date('v', $time);
                break;

            case 3:
                return $time;
                break;
        }
        /* $last_elapsed_time = $this->stop - $this->start;
        return $last_elapsed_time; */
    }

    /**
     *
     */
    public function getElapsedTime(): float
    {
        return array_sum($this->interval);
    }
}
