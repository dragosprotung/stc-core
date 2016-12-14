<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tracker;

use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Workout;
use SportTrackerConnector\Core\Workout\WorkoutIdInterface;

/**
 * Interface for trackers.
 */
interface TrackerInterface
{
    /**
     * Get the ID of the tracker.
     *
     * @return string
     */
    public static function ID(): string;

    /**
     * Fetch a list of workouts from the tracker.
     *
     * @param \DateTimeImmutable $startDate The start date for the workouts.
     * @param \DateTimeImmutable $endDate The end date for the workouts.
     * @return Workout[]
     */
    public function workouts(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array;

    /**
     * Fetch a workout from the tracker.
     *
     * @param WorkoutIdInterface $idWorkout The ID of the workout to download.
     * @return Workout
     */
    public function workout(WorkoutIdInterface $idWorkout): Workout;

    /**
     * Upload a workout to the tracker.
     *
     * @param Workout $workout The workout to upload.
     * @return boolean
     */
    public function save(Workout $workout): bool;

    /**
     * Get the sport mapper.
     *
     * @return SportMapperInterface
     */
    public function sportMapper(): SportMapperInterface;
}
