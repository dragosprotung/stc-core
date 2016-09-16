<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;

/**
 * A point in a track.
 */
class TrackPoint
{
    /**
     * Latitude of the point.
     *
     * @var float
     */
    protected $latitude;

    /**
     * Longitude of the point.
     *
     * @var float
     */
    protected $longitude;

    /**
     * The distance in meters from start to this point.
     *
     * @var float
     */
    protected $distance;

    /**
     * Elevation of the point.
     *
     * @var float
     */
    protected $elevation;

    /**
     * The time for the point.
     *
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * Array of extensions.
     *
     * @var ExtensionInterface[]
     */
    protected $extensions = array();

    /**
     * @param float $latitude The latitude.
     * @param float $longitude The longitude.
     * @param \DateTime $dateTime The date and time of the point.
     */
    public function __construct(float $latitude, float $longitude, \DateTime $dateTime)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->dateTime = $dateTime;
    }

    /**
     * Set the elevation.
     *
     * @param float $elevation The elevation.
     */
    public function setElevation(float $elevation)
    {
        $this->elevation = $elevation;
    }

    /**
     * Get the elevation.
     *
     * @return float
     */
    public function elevation() : float
    {
        return $this->elevation;
    }

    /**
     * Set the extensions.
     *
     * @param ExtensionInterface[] $extensions The extensions to set.
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = array();
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }

    /**
     * Get the extensions.
     *
     * @return ExtensionInterface[]
     */
    public function extensions()
    {
        return $this->extensions;
    }

    /**
     * Add an extension to the workout.
     *
     * @param ExtensionInterface $extension The extension to add.
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[$extension::ID()] = $extension;
    }

    /**
     * Check if an extension is present.
     *
     * @param string $idExtension The ID of the extension.
     * @return boolean
     */
    public function hasExtension($idExtension)
    {
        return array_key_exists($idExtension, $this->extensions);
    }

    /**
     * Get an extension by ID.
     *
     * @param string $idExtension The ID of the extension.
     * @return ExtensionInterface
     * @throws \OutOfBoundsException If the extension is not found.
     */
    public function extension($idExtension)
    {
        if ($this->hasExtension($idExtension) !== true) {
            throw new \OutOfBoundsException(sprintf('Extension "%s" not found.', $idExtension));
        }

        return $this->extensions[$idExtension];
    }

    /**
     * Get the latitude.
     *
     * @return float
     */
    public function latitude() : float
    {
        return $this->latitude;
    }

    /**
     * Get the longitude.
     *
     * @return float
     */
    public function longitude() : float
    {
        return $this->longitude;
    }

    /**
     * Get the date time of the point.
     *
     * @return \DateTime
     */
    public function dateTime() : \DateTime
    {
        return $this->dateTime;
    }

    /**
     * Set the distance from start to this point.
     *
     * @param float $distance The distance from start to this point.
     */
    public function setDistance($distance = null)
    {
        if ($distance !== null) {
            $distance = (float)$distance;
        }

        $this->distance = $distance;
    }

    /**
     * Check if the point has a distance set from start to this point.
     *
     * @return boolean
     */
    public function hasDistance() : bool
    {
        return $this->distance !== null;
    }

    /**
     * Get the distance from start to this point.
     *
     * @return float
     */
    public function distance()
    {
        return $this->distance;
    }

    /**
     * Get the distance between this point and another point in meters.
     *
     * @param TrackPoint $trackPoint The other point.
     * @return float The distance in meters.
     */
    public function distanceFromPoint(TrackPoint $trackPoint)  : float
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($this->latitude());
        $lonFrom = deg2rad($this->longitude());
        $latTo = deg2rad($trackPoint->latitude());
        $lonTo = deg2rad($trackPoint->longitude());

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(
                sqrt(
                    pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
                )
            );

        return $angle * $earthRadius;
    }

    /**
     * Get the speed between this point and another point in km/h.
     *
     * @param TrackPoint $trackPoint The other point.
     * @return float
     */
    public function speed(TrackPoint $trackPoint)  : float
    {
        $start = $this->dateTime();
        $end = $trackPoint->dateTime();
        $dateDiff = $start->diff($end);
        $secondsDifference = $dateDiff->days * 86400 + $dateDiff->h * 3600 + $dateDiff->i * 60 + $dateDiff->s;

        if ($secondsDifference === 0) {
            return 0.0;
        }

        if ($this->hasDistance() === true && $trackPoint->hasDistance()) {
            $distance = abs($this->distance() - $trackPoint->distance());
        } else {
            $distance = $this->distanceFromPoint($trackPoint);
        }

        return ($distance / $secondsDifference) * 3.6;
    }
}
