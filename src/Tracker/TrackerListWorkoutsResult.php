<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tracker;

/**
 * One result item when fetching a list of workouts from a tracker.
 */
final class TrackerListWorkoutsResult
{
    /**
     * The ID of the workout.
     *
     * @var integer
     */
    private $idWorkout;

    /**
     * The sport. One of the constants from SportMapperInterface.
     *
     * @var string
     */
    private $sport;

    /**
     * The start date time of the workout.
     *
     * @var \DateTime
     */
    private $startDateTime;

    /**
     * @param string $idWorkout The ID of the workout.
     * @param string $sport The sport. One of the constants from SportMapperInterface.
     * @param \DateTime $startDateTime The start date time of the workout.
     */
    public function __construct(string $idWorkout, string $sport, \DateTime $startDateTime)
    {
        $this->idWorkout = $idWorkout;
        $this->sport = $sport;
        $this->startDateTime = $startDateTime;
    }

    /**
     * @return int
     */
    public function idWorkout(): int
    {
        return $this->idWorkout;
    }

    /**
     * @return string
     */
    public function sport(): string
    {
        return $this->sport;
    }

    /**
     * @return \DateTime
     */
    public function startDateTime(): \DateTime
    {
        return $this->startDateTime;
    }
}
