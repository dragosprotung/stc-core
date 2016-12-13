<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Loader;

use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\SportGuesser;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Load a workout from GPX format.
 */
class GPX implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(string $filePath): Workout
    {
        $simpleXML = new \SimpleXMLElement($filePath);

        $author = null;
        if (isset($simpleXML->metadata->author->name)) {
            $author = new Author((string)$simpleXML->metadata->author->name);
        }

        $tracks = [];
        foreach ($simpleXML->trk as $simpleXMLTrack) {
            // Sport.
            $sport = SportMapperInterface::class;
            if (isset($simpleXMLTrack->type)) {
                $sport = SportGuesser::guess((string)$simpleXMLTrack->type);
            }

            $points = [];
            // Track points.
            foreach ($simpleXMLTrack->trkseg->trkpt as $point) {
                $attributes = $point->attributes();

                $trackPoint = TrackPoint::with(
                    (float)$attributes['lat'],
                    (float)$attributes['lon'],
                    new \DateTimeImmutable((string)$point->time),
                    (float)$point->ele,
                    $this->parseExtensions($point->extensions)
                );

                $points[] = $trackPoint;
            }
            $track = new Track($points, $sport);

            $tracks[] = $track;
        }

        return new Workout($tracks, $author);
    }

    /**
     * Parse and return an array of extensions from the XML.
     *
     * @param \SimpleXMLElement $extensions The extensions to parse.
     * @return ExtensionInterface[]
     */
    protected function parseExtensions(\SimpleXMLElement $extensions): array
    {
        $extensions = $extensions->asXML();
        $return = array();
        if (preg_match('/<gpxtpx:hr>(.*)<\/gpxtpx:hr>/', $extensions, $matches)) {
            $return[] = HR::fromValue($matches[1]);
        }

        return $return;
    }
}
