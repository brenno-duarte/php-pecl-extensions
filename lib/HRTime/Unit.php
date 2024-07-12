<?php

namespace HRTime;

if (!extension_loaded("hrtime")) {
    enum Unit
    {
        const SECOND = 0;
        const MILLISECOND = 1;
        const MICROSECOND = 2;
        const NANOSECOND = 3;
    }
}
