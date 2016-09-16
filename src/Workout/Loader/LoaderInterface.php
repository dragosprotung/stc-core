<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Loader;

use League\Flysystem\UnreadableFileException;
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
    public function fromString($string) : Workout;

    /**
     * Get a workout from a file.
     *
     * @param string $file The path to the file to load.
     * @return Workout
     *
     * @throws UnreadableFileException
     */
    public function fromFile($file) : Workout;
}
