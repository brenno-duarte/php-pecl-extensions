<?php

namespace PeclPolyfill\HRTime;

class PerformanceCounter
{
    public static function getFrequency(): int
    {
        return hrtime(true);
    }
    public static function getTicks(): int
    {
        
    }
    public static function getTicksSince(int $start): int
    {
    }
}
