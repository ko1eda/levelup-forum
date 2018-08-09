<?php

namespace App\Traits\Views;

use Illuminate\Support\Facades\Redis;

trait RecordViews
{
    /**
     * Returns an instance of ViewTracker class
     *
     * @return App\Traits\Views\ViewTracker
     */
    public function views()
    {
        return new ViewTracker(new Redis, $this);
    }
}
