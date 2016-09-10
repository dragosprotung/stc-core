<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Loader;

use DateTime;
use SimpleXMLElement;
use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\SportGuesser;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Load a workout from GPX format.
 */
class GPX extends AbstractLoader
{
    /**
     * {@inheritdoc}
     */
    public function fromString($string)
    {
        $simpleXML = new SimpleXMLElement($string);
        $workout = new Workout();

        if (isset($simpleXML->metadata->author->name)) {
            $workout->setAuthor(new Author($simpleXML->metadata->author->name));
        }

        foreach ($simpleXML->trk as $simpleXMLTrack) {
            $track = new Track();

            // Sport.
            if (isset($simpleXMLTrack->type)) {
                $track->setSport(SportGuesser::getSportFromCode((string)$simpleXMLTrack->type));
            }

            // Track points.
            foreach ($simpleXMLTrack->trkseg->trkpt as $point) {
                $attributes = $point->attributes();
                $dateTime = new DateTime((string)$point->time);
                $trackPoint = new TrackPoint((float)$attributes['lat'], (float)$attributes['lon'], $dateTime);
                $trackPoint->setElevation((int)$point->ele);
                if (isset($point->extensions)) {
                    $trackPoint->setExtensions($this->parseExtensions($point->extensions));
                }

                $track->addTrackPoint($trackPoint);
            }

            $workout->addTrack($track);
        }


        return $workout;
    }

    /**
     * Parse and return an array of extensions from the XML.
     *
     * @param SimpleXMLElement $extensions The extensions to parse.
     * @return ExtensionInterface[]
     */
    private function parseExtensions(SimpleXMLElement $extensions)
    {
        $extensions = $extensions->asXML();
        $return = array();
        if (preg_match('/<gpxtpx:hr>(.*)<\/gpxtpx:hr>/', $extensions, $matches)) {
            $return[] = new HR((int)$matches[1]);
        }

        return $return;
    }
}
