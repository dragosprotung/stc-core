<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Loader;

use SportTrackerConnector\Core\Workout\Workout;

/**
 * Interface for workout loaders.
 */
interface LoaderInterface
{
    /**
     * Get a workout from a string.
     *
     * @param string $filePath The data.
     * @return Workout
     */
    public function load(string $filePath): Workout;
}
