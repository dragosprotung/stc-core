<?php

namespace SportTrackerConnector\Core\Tracker;

use DateTime;
use DateTimeZone;
use Psr\Log\LoggerAwareInterface;
use SportTrackerConnector\Core\Workout\Workout;
use Psr\Log\LoggerInterface;

/**
 * Interface for trackers.
 */
interface TrackerInterface extends LoggerAwareInterface
{

    /**
     * Get a new instance using a config array.
     *
     * @param LoggerInterface $logger The logger.
     * @param array $config The config for the new instance.
     * @return TrackerInterface
     */
    public static function fromConfig(LoggerInterface $logger, array $config);

    /**
     * Get the ID of the tracker.
     *
     * @return string
     */
    public static function getID();

    /**
     * Set the timezone of the tracker.
     *
     * @param DateTimeZone $timeZone The timezone.
     * @return void
     */
    public function setTimeZone(DateTimeZone $timeZone);

    /**
     * Get the timezone of the tracker.
     *
     * @return DateTimeZone
     */
    public function getTimeZone();

    /**
     * Get a list of workouts.
     *
     * @param DateTime $startDate The start date for the workouts.
     * @param DateTime $endDate The end date for the workouts.
     * @return TrackerListWorkoutsResult[]
     */
    public function listWorkouts(DateTime $startDate, DateTime $endDate);

    /**
     * Upload a workout.
     *
     * @param Workout $workout The workout to upload.
     * @return boolean
     */
    public function uploadWorkout(Workout $workout);

    /**
     * Download a workout.
     *
     * @param string $idWorkout The ID of the workout to download.
     * @return Workout
     */
    public function downloadWorkout($idWorkout);

    /**
     * Get the sport mapper.
     *
     * @return \SportTrackerConnector\Core\Workout\SportMapperInterface
     */
    public function getSportMapper();
}
