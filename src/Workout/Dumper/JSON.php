<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Dumper;

use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Dump a workout to JSON.
 */
class JSON implements DumperInterface
{
    /**
     * {@inheritdoc}
     */
    public function dump(Workout $workout): string
    {
        $data = array();
        $tracks = $workout->tracks();
        foreach ($tracks as $track) {
            $data[] = array(
                'workout' => array(
                    'points' => $this->writeTrackPoints($track->trackPoints())
                )
            );
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Write the track points into an array.
     *
     * @param TrackPoint[] $trackPoints The track points to write.
     * @return array
     */
    private function writeTrackPoints(array $trackPoints)
    {
        $points = array();
        $previousPoint = null;
        foreach ($trackPoints as $trackPoint) {
            $dateTime = clone $trackPoint->dateTime();
            $dateTime->setTimezone(new \DateTimeZone('UTC'));

            $distance = 0;
            if ($previousPoint !== null) {
                $distance = $trackPoint->distanceFromPoint($previousPoint);
            }

            $point = array(
                'time' => $dateTime->format(\DateTime::W3C),
                'latitude' => $trackPoint->latitude(),
                'longitude' => $trackPoint->longitude(),
                'elevation' => $trackPoint->elevation(),
                'distance' => $distance,
                'extensions' => $this->writeExtensions($trackPoint->extensions())
            );
            $previousPoint = $trackPoint;

            $points[] = $point;
        }

        return $points;
    }

    /**
     * Write the extensions into an array.
     *
     * @param ExtensionInterface[] $extensions The extensions to write.
     * @return array
     */
    protected function writeExtensions(array $extensions)
    {
        $return = array();
        foreach ($extensions as $extension) {
            $return[$extension::ID()] = $extension->value();
        }

        return $return;
    }
}
