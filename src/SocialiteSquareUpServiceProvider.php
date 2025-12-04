<?php

namespace Codewize\SquareupSocialite;

use Socialite;
use Illuminate\Support\ServiceProvider;

class SocialiteSquareUpServiceProvider extends ServiceProvider
{
  /**
   * Perform post-registration booting of services.
   *
   * @return void
   */
  public function boot()
  {
    Socialite::extend('squareup', function ($app) {
      $config = $app['config']['squareup_oauth'];

      // Publish config
      $this->publishes([
          __DIR__.'/../config/squareup.php' => config_path('squareup.php'),
      ], 'squareup-config');

      $this->publishes([
          __DIR__.'/SocialiteSquareUpServiceProvider.php' => app_path('Providers/SocialiteSquareUpServiceProvider.php'),
      ], 'squareup-provider');

      return Socialite::buildProvider(SocialiteSquareUpProvider::class, $config);
    });
  }

  /**
   * Register any package services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}