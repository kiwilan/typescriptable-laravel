<?php

namespace Kiwilan\Typescriptable\Tests\Data\Observers;

use Kiwilan\Typescriptable\Tests\Data\Models\Story;

class StoryObserver
{
    /**
     * Handle the Story "created" event.
     */
    public function created(Story $story): void
    {
        // ...
    }

    /**
     * Handle the Story "updated" event.
     */
    public function updated(Story $story): void
    {
        // ...
    }

    /**
     * Handle the Story "deleted" event.
     */
    public function deleted(Story $story): void
    {
        // ...
    }

    /**
     * Handle the Story "restored" event.
     */
    public function restored(Story $story): void
    {
        // ...
    }

    /**
     * Handle the Story "forceDeleted" event.
     */
    public function forceDeleted(Story $story): void
    {
        // ...
    }
}
