<?php

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
     * @param string $string The data.
     * @return Workout
     */
    public function fromString($string);

    /**
     * Get a workout from a file.
     *
     * @param string $file The path to the file to load.
     * @return Workout
     */
    public function fromFile($file);
}
