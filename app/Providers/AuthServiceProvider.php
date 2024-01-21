<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        $this->registerPolicies();

        Gate::define('start-chat', function ($userSending, $userToSendTo) {
            $contacts = $userToSendTo->contacts()->get();
            // dd($userSending->id, $userToSendTo->id);
            if($userSending->blacklist->contains($userToSendTo->id))
            {
                return false;
            }
            if($userToSendTo->blacklist->contains($userSending))
            {
                return false;
            }
           return $contacts->contains('id', $userSending->id);
        });
    }
}
