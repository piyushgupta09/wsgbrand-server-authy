<?php
namespace Fpaipl\Authy;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Fpaipl\Authy\Http\Livewire\AddAddress;
use Fpaipl\Authy\View\Components\AuthLink;
use Fpaipl\Authy\View\Components\AuthToast;
use Fpaipl\Authy\Http\Livewire\UserAssignRoles;

class AuthyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'authy');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewComponentsAs('authy', [
            AuthToast::class,
            AuthLink::class,
        ]);

        Livewire::component('add-address', AddAddress::class);
        Livewire::component('user-assign-roles', UserAssignRoles::class);
    }
}
