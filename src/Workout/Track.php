<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

use DateTime;
use SportTrackerConnector\Core\Date\DateInterval;

/**
 * A track of a workout.
 */
class Track
{
    /**
     * The sport for the workout.
     *
     * @var string
     */
    protected $sport = SportMapperInterface::OTHER;

    /**
     * The track points of this track.
     *
     * @var TrackPoint[]
     */
    protected $trackPoints = array();

    /**
     * The start date and time of the track.
     *
     * @var DateTime
     */
    protected $startDateTime;

    /**
     * The end date and time of the track.
     *
     * @var DateTime
     */
    protected $endDateTime;

    /**
     * Get the length of the track in meters.
     *
     * @var float
     */
    protected $length = 0;

    /**
     * Constructor.
     *
     * @param TrackPoint[] $trackPoints The track points.
     * @param string $sport The sport for this track.
     */
    public function __construct(array $trackPoints = array(), string $sport = SportMapperInterface::OTHER)
    {
        $this->setTrackPoints($trackPoints);
        $this->setSport($sport);
    }

    /**
     * Set the sport for this workout.
     *
     * @param string $sport The sport.
     */
    public function setSport(string $sport)
    {
        $this->sport = $sport;
    }

    /**
     * Get the sport of the workout.
     *
     * @return string
     */
    public function getSport() : string
    {
        return $this->sport;
    }

    /**
     * Add a track point.
     *
     * @param TrackPoint $trackPoint The track point to add.
     */
    public function addTrackPoint(TrackPoint $trackPoint)
    {
        $this->trackPoints[] = $trackPoint;
    }

    /**
     * Set the track points.
     *
     * @param TrackPoint[] $trackPoints The track points to set.
     */
    public function setTrackPoints(array $trackPoints)
    {
        $this->trackPoints = $trackPoints;
    }

    /**
     * Get the track points.
     *
     * @return TrackPoint[]
     */
    public function getTrackPoints()
    {
        return $this->trackPoints;
    }

    /**
     * Get the last track point.
     *
     * @return TrackPoint
     * @throws \OutOfBoundsException If no track points are defined.
     */
    public function getLastTrackPoint() : TrackPoint
    {
        $lastTrackPoint = end($this->trackPoints);

        if (!$lastTrackPoint instanceof TrackPoint) {
            throw new \OutOfBoundsException('No track points points defined.');
        }

        reset($this->trackPoints);

        return $lastTrackPoint;
    }

    /**
     * Set the start date and time of the track.
     *
     * @param DateTime $startDateTime The start date and time.
     */
    public function setStartDateTime(DateTime $startDateTime)
    {
        $this->startDateTime = $startDateTime;
    }

    /**
     * Get the start date and time of the track.
     *
     * @return DateTime
     */
    public function getStartDateTime()
    {
        if ($this->startDateTime === null) {
            $this->recomputeStartDateTime();
        }

        return $this->startDateTime;
    }

    /**
     * Recompute the start date and time of the track.
     *
     * @return DateTime
     */
    public function recomputeStartDateTime()
    {
        $this->startDateTime = null;
        foreach ($this->getTrackPoints() as $trackPoint) {
            if ($this->startDateTime === null || $this->startDateTime > $trackPoint->getDateTime()) {
                $this->startDateTime = clone $trackPoint->getDateTime();
            }
        }

        return $this->startDateTime;
    }

    /**
     * Set the end date and time of the track.
     *
     * @param DateTime $endDateTime The end date and time.
     */
    public function setEndDateTime(DateTime $endDateTime)
    {
        $this->endDateTime = $endDateTime;
    }

    /**
     * Get the start date and time of the track.
     *
     * @return DateTime
     */
    public function getEndDateTime()
    {
        if ($this->endDateTime === null) {
            $this->recomputeEndDateTime();
        }

        return $this->endDateTime;
    }

    /**
     * Recompute the start date and time of the track.
     *
     * @return DateTime
     */
    public function recomputeEndDateTime()
    {
        $this->endDateTime = null;
        foreach ($this->getTrackPoints() as $trackPoint) {
            if ($this->startDateTime === null || $this->endDateTime < $trackPoint->getDateTime()) {
                $this->endDateTime = clone $trackPoint->getDateTime();
            }
        }

        return $this->endDateTime;
    }

    /**
     * Get the duration of the track.
     *
     * @return DateInterval
     */
    public function getDuration()
    {
        $start = $this->getStartDateTime();
        $end = $this->getEndDateTime();

        $dateDifference = $start->diff($end);

        $dateInterval = new DateInterval('PT1S');
        $dateInterval->y = $dateDifference->y;
        $dateInterval->m = $dateDifference->m;
        $dateInterval->d = $dateDifference->d;
        $dateInterval->h = $dateDifference->h;
        $dateInterval->i = $dateDifference->i;
        $dateInterval->s = $dateDifference->s;
        $dateInterval->invert = $dateDifference->invert;
        $dateInterval->days = $dateDifference->days;

        return $dateInterval;
    }

    /**
     * Set the length of the track in meters.
     *
     * @param float $length The length of the track in meters.
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Get the length of the track in meters.
     *
     * @return float
     */
    public function getLength()
    {
        if ($this->length === 0) {
            $this->length = $this->recomputeLength();
        }

        return $this->length;

    }

    /**
     * Recompute the length of the track.
     *
     * @return float
     */
    public function recomputeLength()
    {
        $this->length = 0;

        $trackPoints = $this->getTrackPoints();
        $trackPointsCount = count($trackPoints);
        if ($trackPointsCount < 2) {
            return 0;
        }

        for ($i = 1; $i < $trackPointsCount; $i++) {
            $previousTrack = $trackPoints[$i - 1];
            $currentTrack = $trackPoints[$i];

            $this->length += $currentTrack->distance($previousTrack);
        }

        $this->length = round($this->length, 6);

        return $this->length;
    }
}
