<?php

namespace App\Providers;

use App\Comic;
use App\Policies\ComicPolicy;
use App\Series;
use App\Policies\SeriesPolicy;
use App\Collection;
use App\Policies\CollectionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Comic::class => ComicPolicy::class,
        Series::class => SeriesPolicy::class,
        Collection::class => CollectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
