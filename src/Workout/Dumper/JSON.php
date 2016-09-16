<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Dumper;

use DateTime;
use DateTimeZone;
use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Dump a workout to JSON.
 */
class JSON extends AbstractDumper
{
    /**
     * {@inheritdoc}
     */
    public function toString(Workout $workout) : string
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
        foreach ($trackPoints as $trackPoint) {
            $dateTime = clone $trackPoint->dateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $point = array(
                'time' => $dateTime->format(DateTime::W3C),
                'latitude' => $trackPoint->latitude(),
                'longitude' => $trackPoint->longitude(),
                'elevation' => $trackPoint->getElevation(),
                'distance' => $trackPoint->getDistance(),
                'extensions' => $this->writeExtensions($trackPoint->extensions())
            );

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
