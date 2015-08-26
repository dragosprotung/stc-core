<?php

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
    public function dumpToString(Workout $workout)
    {
        $data = array();
        $tracks = $workout->getTracks();
        foreach ($tracks as $track) {
            $data[] = array(
                'workout' => array(
                    'points' => $this->writeTrackPoints($track->getTrackpoints())
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
            $dateTime = clone $trackPoint->getDateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $point = array(
                'time' => $dateTime->format(DateTime::W3C),
                'latitude' => $trackPoint->getLatitude(),
                'longitude' => $trackPoint->getLongitude(),
                'elevation' => $trackPoint->getElevation(),
                'distance' => $trackPoint->getDistance(),
                'extensions' => $this->writeExtensions($trackPoint->getExtensions())
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
            $return[$extension->getID()] = $extension->getValue();
        }

        return $return;
    }
}
