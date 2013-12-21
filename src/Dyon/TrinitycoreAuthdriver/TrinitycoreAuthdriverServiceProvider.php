<?php namespace Dyon\TrinitycoreAuthdriver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Guard;
use App, Auth, Session, Config;

class TrinitycoreAuthdriverServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    public function boot()
    {
        $this->package('dyon/trinitycore-authdriver');

        Auth::extend('trinitycore', function() {
            return new Guard(new Providers\TrinitycoreUserProvider(Config::get('auth.model')), App::make('session.store'));
        });
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
