<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tracker;

use DateTime;
use DateTimeZone;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Workout;

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
    public static function getID() : string;

    /**
     * Get the timezone of the tracker.
     *
     * @return DateTimeZone
     */
    public function getTimeZone() : DateTimeZone;

    /**
     * Get offset between the tracker time zone and UTC time zone in seconds.
     *
     * @return integer
     */
    public function getTimeZoneOffset() : int;

    /**
     * Get a list of workouts.
     *
     * @param DateTime $startDate The start date for the workouts.
     * @param DateTime $endDate The end date for the workouts.
     * @return TrackerListWorkoutsResult[]
     */
    public function listWorkouts(DateTime $startDate, DateTime $endDate) : array;

    /**
     * Upload a workout.
     *
     * @param Workout $workout The workout to upload.
     * @return boolean
     */
    public function uploadWorkout(Workout $workout) : bool;

    /**
     * Download a workout.
     *
     * @param string $idWorkout The ID of the workout to download.
     * @return Workout
     */
    public function downloadWorkout($idWorkout) : Workout;

    /**
     * Get the sport mapper.
     *
     * @return \SportTrackerConnector\Core\Workout\SportMapperInterface
     */
    public function getSportMapper() : SportMapperInterface;
}
