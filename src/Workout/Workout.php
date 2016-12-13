<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

use Assert\Assertion;

/**
 * A workout.
 */
class Workout
{
    /**
     * The author of a workout.
     *
     * @var Author
     */
    private $author;

    /**
     * The tracks of the workout.
     *
     * @var Track[]
     */
    private $tracks = array();

    /**
     * @param Track[] $tracks
     * @param Author|null $author
     */
    public function __construct(array $tracks, ?Author $author = null)
    {
        Assertion::allIsInstanceOf($tracks, Track::class);

        $this->tracks = $tracks;
        $this->author = $author;
    }

    /**
     * Get the author of the workout.
     *
     * @return Author
     */
    public function author(): ?Author
    {
        return $this->author;
    }

    /**
     * Get the tracks.
     *
     * @return Track[]
     */
    public function tracks(): array
    {
        return $this->tracks;
    }
}
