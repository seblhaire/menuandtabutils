<?php namespace Seblhaire\MenuAndTabUtils;

use Illuminate\Support\Facades\Facade;

class BreadcrumbUtils extends Facade{

  protected static function getFacadeAccessor()
  {
      return BreadcrumbService::class;
  }

}
