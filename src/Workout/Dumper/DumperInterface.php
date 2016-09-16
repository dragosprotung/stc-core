<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Dumper;

use SportTrackerConnector\Core\Workout\Workout;

/**
 * Interface for workout dumpers.
 */
interface DumperInterface
{
    /**
     * Dump a workout to string.
     *
     * @param Workout $workout The workout to dump.
     * @return string
     */
    public function toString(Workout $workout) : string;

    /**
     * Dump a workout to a file.
     *
     * @param Workout $workout The workout to dump.
     * @param string $outputFile The path to file where to dump the workout.
     * @return boolean
     */
    public function toFile(Workout $workout, string $outputFile) : bool;
}
