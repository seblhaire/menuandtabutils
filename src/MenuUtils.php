<?php

namespace Seblhaire\MenuAndTabUtils;

use Illuminate\Support\Facades\Facade;

class MenuUtils extends Facade {

    protected static function getFacadeAccessor() {
        return MenuService::class;
    }
}
