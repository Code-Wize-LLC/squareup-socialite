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
      $config = $app['config']['services.squareup'];

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