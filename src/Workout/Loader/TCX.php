<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Loader;

use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\SportGuesser;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Load a workout from TCX format.
 */
class TCX extends AbstractLoader
{
    /**
     * {@inheritdoc}
     */
    public function fromString($string) : Workout
    {
        $simpleXML = new \SimpleXMLElement($string);
        $workout = new Workout();

        foreach ($simpleXML->Activities[0] as $simpleXMLActivity) {
            // Sport.
            $attributes = $simpleXMLActivity->attributes();
            $sport = SportMapperInterface::class;
            if (isset($attributes['Sport'])) {
                $sport = SportGuesser::sportFromCode((string)$attributes['Sport']);
            }

            $workoutTrack = new Track(array(), $sport);

            // Track points.
            foreach ($simpleXMLActivity->Lap as $lap) {
                foreach ($lap->Track as $track) {
                    foreach ($track->Trackpoint as $trackPoint) {
                        $dateTime = new \DateTime((string)$trackPoint->Time);
                        $latitude = (float)$trackPoint->Position->LatitudeDegrees;
                        $longitude = (float)$trackPoint->Position->LongitudeDegrees;

                        $workoutTrackPoint = new TrackPoint($latitude, $longitude, $dateTime);
                        $workoutTrackPoint->setElevation(round((float)$trackPoint->AltitudeMeters, 2));

                        if ($trackPoint->DistanceMeters) {
                            $workoutTrackPoint->setDistance($trackPoint->DistanceMeters);
                        }

                        $extensions = $this->parseExtensions($trackPoint);
                        $workoutTrackPoint->setExtensions($extensions);

                        $workoutTrack->addTrackPoint($workoutTrackPoint);
                    }
                }
            }

            $workout->addTrack($workoutTrack);
        }

        return $workout;
    }

    /**
     * Parse and return an array of extensions from the XML.
     *
     * @param \SimpleXMLElement $trackPoint The track point from the TCX to parse.
     * @return ExtensionInterface[]
     */
    protected function parseExtensions(\SimpleXMLElement $trackPoint) : array
    {
        $return = array();

        if ($trackPoint->HeartRateBpm) {
            $return[] = new HR((int)$trackPoint->HeartRateBpm->Value);
        }

        return $return;
    }
}
