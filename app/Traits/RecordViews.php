<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;
use App\POPO\StatsTracker;

trait RecordViews
{
    /**
     * Returns an instance of StatsTracker class
     * set to record views
     *
     * @return App\POPO\StatsTracker
     */
    public function views()
    {
        return StatsTracker::track($this, 'views');
    }
}
