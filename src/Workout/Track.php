<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

use Assert\Assertion;
use SportTrackerConnector\Core\Date\DateInterval;

/**
 * A track of a workout.
 */
final class Track
{
    /**
     * The sport for the workout.
     *
     * @var string
     */
    private $sport = SportMapperInterface::OTHER;

    /**
     * The track points of this track.
     *
     * @var TrackPoint[]
     */
    private $trackPoints = array();

    /**
     * The start date and time of the track.
     *
     * @var \DateTimeImmutable
     */
    private $startDateTime;

    /**
     * The end date and time of the track.
     *
     * @var \DateTimeImmutable
     */
    private $endDateTime;

    /**
     * Get the length of the track in meters.
     *
     * @var float
     */
    private $length = 0;

    /**
     * Constructor.
     *
     * @param TrackPoint[] $trackPoints The track points.
     * @param string $sport The sport for this track.
     */
    public function __construct(array $trackPoints = array(), string $sport = SportMapperInterface::OTHER)
    {
        Assertion::allIsInstanceOf($trackPoints, TrackPoint::class);

        $this->trackPoints = $trackPoints;
        $this->sport = $sport;
    }

    /**
     * Get the sport of the workout.
     *
     * @return string
     */
    public function sport(): string
    {
        return $this->sport;
    }

    /**
     * Get the track points.
     *
     * @return TrackPoint[]
     */
    public function trackPoints()
    {
        return $this->trackPoints;
    }

    /**
     * Get the last track point.
     *
     * @return TrackPoint
     * @throws \OutOfBoundsException If no track points are defined.
     */
    public function lastTrackPoint(): TrackPoint
    {
        $lastTrackPoint = end($this->trackPoints);

        if (!$lastTrackPoint instanceof TrackPoint) {
            throw new \OutOfBoundsException('No track points points defined.');
        }

        reset($this->trackPoints);

        return $lastTrackPoint;
    }

    /**
     * Get the start date and time of the track.
     *
     * @return \DateTimeImmutable
     */
    public function startDateTime(): \DateTimeImmutable
    {
        if ($this->startDateTime === null) {
            $this->recomputeStartDateTime();
        }

        return $this->startDateTime;
    }

    /**
     * Recompute the start date and time of the track.
     *
     * @return \DateTimeImmutable
     */
    private function recomputeStartDateTime(): \DateTimeImmutable
    {
        $this->startDateTime = null;
        foreach ($this->trackPoints() as $trackPoint) {
            if ($this->startDateTime === null || $this->startDateTime > $trackPoint->dateTime()) {
                $this->startDateTime = clone $trackPoint->dateTime();
            }
        }

        return $this->startDateTime;
    }

    /**
     * Get the start date and time of the track.
     *
     * @return \DateTimeImmutable
     */
    public function endDateTime(): \DateTimeImmutable
    {
        if ($this->endDateTime === null) {
            $this->recomputeEndDateTime();
        }

        return $this->endDateTime;
    }

    /**
     * Recompute the start date and time of the track.
     *
     * @return \DateTimeImmutable
     */
    private function recomputeEndDateTime(): \DateTimeImmutable
    {
        $this->endDateTime = null;
        foreach ($this->trackPoints() as $trackPoint) {
            if ($this->startDateTime === null || $this->endDateTime < $trackPoint->dateTime()) {
                $this->endDateTime = clone $trackPoint->dateTime();
            }
        }

        return $this->endDateTime;
    }

    /**
     * Get the duration of the track.
     *
     * @return DateInterval
     */
    public function duration()
    {
        $start = $this->startDateTime();
        $end = $this->endDateTime();

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
     * @deprecated
     */
    public function setLength(float $length)
    {
        $this->length = $length;
    }

    /**
     * Get the length of the track in meters.
     *
     * @return float
     */
    public function length(): float
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
    private function recomputeLength(): float
    {
        $this->length = 0;

        $trackPoints = $this->trackPoints();
        $trackPointsCount = count($trackPoints);
        if ($trackPointsCount < 2) {
            return 0;
        }

        for ($i = 1; $i < $trackPointsCount; $i++) {
            $previousTrack = $trackPoints[$i - 1];
            $currentTrack = $trackPoints[$i];

            $this->length += $currentTrack->distanceFromPoint($previousTrack);
        }

        $this->length = round($this->length, 6);

        return $this->length;
    }
}
