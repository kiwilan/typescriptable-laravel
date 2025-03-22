<?php

namespace Kiwilan\Typescriptable\Tests\Data\Observers;

use App\Models\Movie;

class MovieObserver
{
    /**
     * Handle the Movie "created" event.
     */
    public function created(Movie $movie): void
    {
        // ...
    }

    /**
     * Handle the Movie "updated" event.
     */
    public function updated(Movie $movie): void
    {
        // ...
    }

    /**
     * Handle the Movie "deleted" event.
     */
    public function deleted(Movie $movie): void
    {
        // ...
    }

    /**
     * Handle the Movie "restored" event.
     */
    public function restored(Movie $movie): void
    {
        // ...
    }

    /**
     * Handle the Movie "forceDeleted" event.
     */
    public function forceDeleted(Movie $movie): void
    {
        // ...
    }
}
