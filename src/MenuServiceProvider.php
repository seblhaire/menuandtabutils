<?php
namespace Seblhaire\MenuAndTabUtils;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider{
  protected $defer = true;

  public function boot(){
    $this->publishes([
        __DIR__.'/../config/menuutils.php' => config_path('vendor/seblhaire/menuutils.php'),
        __DIR__.'/../config/tabutils.php' => config_path('vendor/seblhaire/tabutils.php'),
    ]);
  }

  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/menuutils.php', 'menuutils');
    $this->mergeConfigFrom(__DIR__ . '/../config/breadcrumbutils.php', 'breadcrumbutils');
    $this->mergeConfigFrom(__DIR__ . '/../config/tabutils.php', 'tabutils');
    $this->app->bind(MenuService::class, function($app) {
          return new MenuService;
    });
    $this->app->bind(BreadcrumbService::class, function($app) {
          return new BreadcrumbService;
    });
    $this->app->bind(TabService::class, function($app) {
          return new TabService;
    });
  }

  public function provides() {
      return [MenuServiceContract::class, TabServiceContract::class];
  }
}
