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
    public function dump(Workout $workout): string;
}
