<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
    public function boot()
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https'); // por google cloud run
        }


         Gate::before(
            //NO RETORNAR NADA EN CASO CONTRARIO YA QUE ASUME LOS DEMAS
            function ($user, $abilitys) {
                if ($user->is_admin) {
                    return true;
                }
            }
        );

        try {
            Permission::get(['id', 'nombre'])
                ->map(function ($permission) {
                    Gate::define(
                        $permission->nombre,
                        function (User $user) use ($permission) {
                            return $user->hasPermission($permission->id);
                        }
                    );
                });
        } catch (\Illuminate\Database\QueryException $ex) {
        }
    }
}
