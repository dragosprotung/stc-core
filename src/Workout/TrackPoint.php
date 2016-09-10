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
     * Constructor.
     *
     * @param float $latitude The latitude.
     * @param float $longitude The longitude.
     * @param \DateTime $dateTime The date and time of the point.
     */
    public function __construct(float $latitude, float $longitude, \DateTime $dateTime)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        $this->setDateTime($dateTime);
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
    public function getElevation() : float
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
    public function getExtensions()
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
    public function getExtension($idExtension)
    {
        if ($this->hasExtension($idExtension) !== true) {
            throw new \OutOfBoundsException(sprintf('Extension "%s" not found.', $idExtension));
        }

        return $this->extensions[$idExtension];
    }

    /**
     * Set the latitude.
     *
     * @param float $latitude The latitude.
     */
    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get the latitude.
     *
     * @return float
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }

    /**
     * Set the longitude.
     * @param float $longitude The longitude.
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get the longitude.
     *
     * @return float
     */
    public function getLongitude() : float
    {
        return $this->longitude;
    }

    /**
     * Set the date time of the point.
     *
     * @param \DateTime $dateTime The date time of the point.
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * Get the date time of the point.
     *
     * @return \DateTime
     */
    public function getDateTime() : \DateTime
    {
        return $this->dateTime;
    }

    /**
     * Set the distance from start to this point.
     *
     * @param float $distance The distance from start to this point.
     */
    public function setDistance($distance)
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
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Get the distance between this point and another point in meters.
     *
     * @param TrackPoint $trackPoint The other point.
     * @return float The distance in meters.
     */
    public function distance(TrackPoint $trackPoint)  : float
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($this->getLatitude());
        $lonFrom = deg2rad($this->getLongitude());
        $latTo = deg2rad($trackPoint->getLatitude());
        $lonTo = deg2rad($trackPoint->getLongitude());

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
        $start = $this->getDateTime();
        $end = $trackPoint->getDateTime();
        $dateDiff = $start->diff($end);
        $secondsDifference = $dateDiff->days * 86400 + $dateDiff->h * 3600 + $dateDiff->i * 60 + $dateDiff->s;

        if ($secondsDifference === 0) {
            return 0.0;
        }

        if ($this->hasDistance() === true && $trackPoint->hasDistance()) {
            $distance = abs($this->getDistance() - $trackPoint->getDistance());
        } else {
            $distance = $this->distance($trackPoint);
        }

        return ($distance / $secondsDifference) * 3.6;
    }
}
