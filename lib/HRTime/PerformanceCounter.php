<?php

//declare(ticks=1);

namespace HRTime;

if (!extension_loaded("hrtime")) {
    class PerformanceCounter
    {
        public function __construct()
        {
            throw new \Exception('PerformanceCounter not implemented. Use "StopWatch" class instead');
            //register_tick_function([$this, 'tickHandler']);
        }

        /* public function getFrequency(): int
        {
        }

        public function getTicks(): int
        {
        }

        public function getTicksSince(int $start): int
        {
            return hrtime(true);
        }

        public function tickHandler()
        {
            echo "tick_handler() called\n";
        } */
    }
}
