<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        Gate::before(
            //NO RETORNAR NADA EN CASO CONTRARIO YA QUE ASUME LOS DEMAS
            function ($user, $abilitys) 
            {
                if ($user->is_admin)
                 {
                    return true;
                }
            }
        );

        try 
        {
            Permission::get(['id', 'nombre'])
                ->map(function ($permission) {
                    Gate::define(
                        $permission->nombre,
                        function (User $user) use ($permission)
                         {
                            return $user->HasPermission($permission->id);
                        }
                    );
                });
        } 
        catch (\Illuminate\Database\QueryException $ex) 
        {

        }
    }
}
