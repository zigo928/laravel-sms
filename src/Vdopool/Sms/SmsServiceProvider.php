<?php namespace Vdopool\Sms;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('vdopool/sms');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
          $this->app['sms'] = $this->app->share(function($app)
          {
            return new Sms($app);
          });

          $this->app->booting(function()
          {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Sms', 'Vdopool\Sms\Facades\Sms');
          });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('sms');
	}

}
