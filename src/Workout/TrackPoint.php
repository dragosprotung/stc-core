<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

use Assert\Assertion;
use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;

/**
 * A point in a track.
 */
final class TrackPoint
{
    /**
     * Latitude of the point.
     *
     * @var float
     */
    private $latitude;

    /**
     * Longitude of the point.
     *
     * @var float
     */
    private $longitude;

    /**
     * Elevation of the point.
     *
     * @var float
     */
    private $elevation;

    /**
     * The time for the point.
     *
     * @var \DateTimeImmutable
     */
    private $dateTime;

    /**
     * Array of extensions.
     *
     * @var ExtensionInterface[]
     */
    private $extensions = array();

    /**
     * @param float $latitude The latitude.
     * @param float $longitude The longitude.
     * @param \DateTimeImmutable $dateTime The date and time of the point.
     * @param float|null $elevation
     * @param array $extensions
     */
    private function __construct(
        ?float $latitude,
        ?float $longitude,
        \DateTimeImmutable $dateTime,
        ?float $elevation,
        array $extensions = []
    ) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->dateTime = $dateTime;
        $this->elevation = $elevation;

        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }

    /**
     * Add an extension to the workout.
     *
     * @param ExtensionInterface $extension The extension to add.
     */
    private function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[$extension::ID()] = $extension;
    }

    /**
     * @param float|null $latitude
     * @param float|null $longitude
     * @param \DateTimeImmutable $dateTime
     * @param float|null $elevation
     * @param array $extensions
     * @return TrackPoint
     */
    public static function with(
        ?float $latitude,
        ?float $longitude,
        \DateTimeImmutable $dateTime,
        ?float $elevation = null,
        array $extensions = []
    ): TrackPoint {
        Assertion::greaterOrEqualThan($latitude, -180);
        Assertion::greaterOrEqualThan($longitude, -180);
        Assertion::lessOrEqualThan($latitude, 180);
        Assertion::lessOrEqualThan($longitude, 180);
        Assertion::allIsInstanceOf($extensions, ExtensionInterface::class);

        return new static($latitude, $longitude, $dateTime, $elevation, $extensions);
    }

    /**
     * Get the elevation.
     *
     * @return float
     */
    public function elevation(): float
    {
        return $this->elevation;
    }

    /**
     * Get the extensions.
     *
     * @return ExtensionInterface[]
     */
    public function extensions(): array
    {
        return array_values($this->extensions);
    }


    /**
     * Check if an extension is present.
     *
     * @param string $idExtension The ID of the extension.
     * @return boolean
     */
    public function hasExtension(string $idExtension): bool
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
    public function extension($idExtension): ExtensionInterface
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
    public function latitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Get the longitude.
     *
     * @return float
     */
    public function longitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Get the date time of the point.
     *
     * @return \DateTimeImmutable
     */
    public function dateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * Get the distance between this point and another point in meters.
     *
     * @param TrackPoint $trackPoint The other point.
     * @return float The distance in meters.
     */
    public function distanceFromPoint(TrackPoint $trackPoint): float
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
    public function speed(TrackPoint $trackPoint): float
    {
        $start = $this->dateTime();
        $end = $trackPoint->dateTime();
        $dateDiff = $start->diff($end);
        $secondsDifference = ($dateDiff->days * 86400) + ($dateDiff->h * 3600) + ($dateDiff->i * 60) + $dateDiff->s;

        if ($secondsDifference === 0) {
            return 0.0;
        }

        $distance = $this->distanceFromPoint($trackPoint);

        return ($distance / $secondsDifference) * 3.6;
    }
}
