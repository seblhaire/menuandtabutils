<?php

namespace Seblhaire\MenuAndTabUtils;

use Illuminate\Support\Facades\Facade;

class TabUtils extends Facade {

    protected static function getFacadeAccessor() {
        return TabService::class;
    }
}
