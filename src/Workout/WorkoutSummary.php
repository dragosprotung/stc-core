<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

final class WorkoutSummary
{
    /**
     * The ID of the workout.
     *
     * @var WorkoutIdInterface
     */
    private $workoutId;

    /**
     * The sport. One of the constants from SportMapperInterface.
     *
     * @var string
     */
    private $sport;

    /**
     * The start date time of the workout.
     *
     * @var \DateTimeImmutable
     */
    private $startDateTime;

    /**
     * @param WorkoutIdInterface $workoutId
     * @param string $sport
     * @param \DateTimeImmutable $startDateTime
     */
    public function __construct(WorkoutIdInterface $workoutId, string $sport, \DateTimeImmutable $startDateTime)
    {
        $this->workoutId = $workoutId;
        $this->sport = $sport;
        $this->startDateTime = $startDateTime;
    }

    /**
     * @return WorkoutIdInterface
     */
    public function workoutId(): WorkoutIdInterface
    {
        return $this->workoutId;
    }

    /**
     * @return string
     */
    public function sport(): string
    {
        return $this->sport;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function startDateTime(): \DateTimeImmutable
    {
        return $this->startDateTime;
    }
}
