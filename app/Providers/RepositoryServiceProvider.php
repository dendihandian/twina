<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\TopicRepository::class, \App\Repositories\TopicRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TweetRepository::class, \App\Repositories\TweetRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TopicTweetRepository::class, \App\Repositories\TopicTweetRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PeopleRepository::class, \App\Repositories\PeopleRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TopicGraphRepository::class, \App\Repositories\TopicGraphRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PreferenceRepository::class, \App\Repositories\PreferenceRepositoryEloquent::class);
        //:end-bindings:
    }
}
