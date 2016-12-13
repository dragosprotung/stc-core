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
class TCX implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(string $filePath): Workout
    {
        $simpleXML = new \SimpleXMLElement($filePath);

        $tracks = [];
        foreach ($simpleXML->Activities[0] as $simpleXMLActivity) {
            // Sport.
            $attributes = $simpleXMLActivity->attributes();
            $sport = SportMapperInterface::class;
            if (isset($attributes['Sport'])) {
                $sport = SportGuesser::guess((string)$attributes['Sport']);
            }


            // Track points.
            $trackPoints = [];
            foreach ($simpleXMLActivity->Lap as $lap) {
                foreach ($lap->Track as $track) {
                    foreach ($track->Trackpoint as $trackPoint) {
                        $dateTime = new \DateTimeImmutable((string)$trackPoint->Time);
                        $latitude = (float)$trackPoint->Position->LatitudeDegrees;
                        $longitude = (float)$trackPoint->Position->LongitudeDegrees;

                        $trackPoints[] = TrackPoint::with(
                            $latitude,
                            $longitude,
                            $dateTime,
                            round((float)$trackPoint->AltitudeMeters, 2),
                            $this->parseExtensions($trackPoint)
                        );
                    }
                }
                $tracks[] = new Track($trackPoints, $sport);
            }

        }

        return new Workout($tracks);
    }

    /**
     * Parse and return an array of extensions from the XML.
     *
     * @param \SimpleXMLElement $trackPoint The track point from the TCX to parse.
     * @return ExtensionInterface[]
     */
    protected function parseExtensions(\SimpleXMLElement $trackPoint): array
    {
        $return = array();

        if ($trackPoint->HeartRateBpm) {
            $return[] = HR::fromValue((string)$trackPoint->HeartRateBpm->Value);
        }

        return $return;
    }
}
