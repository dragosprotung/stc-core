<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Dumper;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;
use XMLWriter;

/**
 * Dump a workout to GPX format.
 */
class GPX extends AbstractDumper
{
    /**
     * {@inheritdoc}
     */
    public function toString(Workout $workout) : string
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('gpx');

        $xmlWriter->writeAttribute('version', '1.1');
        $xmlWriter->writeAttribute('creator', 'SportTrackerConnector');
        $xmlWriter->writeAttributeNs(
            'xsi',
            'schemaLocation',
            null,
            'http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensionsv3.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd'
        );
        $xmlWriter->writeAttribute('xmlns', 'http://www.topografix.com/GPX/1/1');
        $xmlWriter->writeAttributeNs(
            'xmlns',
            'gpxtpx',
            null,
            'http://www.garmin.com/xmlschemas/TrackPointExtension/v1'
        );
        $xmlWriter->writeAttributeNs('xmlns', 'gpxx', null, 'http://www.garmin.com/xmlschemas/GpxExtensions/v3');
        $xmlWriter->writeAttributeNs('xmlns', 'xsi', null, 'http://www.w3.org/2001/XMLSchema-instance');

        $this->writeMetaData($xmlWriter, $workout);
        $this->writeTracks($xmlWriter, $workout);

        $xmlWriter->endElement();
        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory(true);
    }

    /**
     * Write the tracks to the GPX.
     *
     * @param XMLWriter $xmlWriter The XML writer.
     * @param Workout $workout The workout.
     */
    private function writeTracks(XMLWriter $xmlWriter, Workout $workout)
    {
        foreach ($workout->tracks() as $track) {
            $xmlWriter->startElement('trk');
            $xmlWriter->writeElement('type', $track->sport());
            $xmlWriter->startElement('trkseg');
            $this->writeTrackPoints($xmlWriter, $track->trackPoints());
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }
    }

    /**
     * Write the track points to the GPX.
     *
     * @param XMLWriter $xmlWriter The XML writer.
     * @param TrackPoint[] $trackPoints The track points to write.
     */
    private function writeTrackPoints(XMLWriter $xmlWriter, array $trackPoints)
    {
        foreach ($trackPoints as $trackPoint) {
            $xmlWriter->startElement('trkpt');

            // Location.
            $xmlWriter->writeAttribute('lat', (string)$trackPoint->latitude());
            $xmlWriter->writeAttribute('lon', (string)$trackPoint->longitude());

            // Elevation.
            $xmlWriter->writeElement('ele', (string)$trackPoint->getElevation());

            // Time of position
            $dateTime = clone $trackPoint->dateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $xmlWriter->writeElement('time', $dateTime->format(DateTime::W3C));

            // Extensions.
            $this->writeExtensions($xmlWriter, $trackPoint->extensions());

            $xmlWriter->endElement();
        }
    }

    /**
     * Write the extensions into the GPX.
     *
     * @param XMLWriter $xmlWriter The XMLWriter.
     * @param ExtensionInterface[] $extensions The extensions to write.
     * @throws InvalidArgumentException If an extension is not known.
     */
    protected function writeExtensions(XMLWriter $xmlWriter, array $extensions)
    {
        $xmlWriter->startElement('extensions');
        foreach ($extensions as $extension) {
            switch ($extension::ID()) {
                case HR::ID():
                    $xmlWriter->startElementNs('gpxtpx', 'TrackPointExtension', null);
                    $xmlWriter->writeElementNs('gpxtpx', 'hr', null, (string)$extension->value());
                    $xmlWriter->endElement();
                    break;
            }
        }
        $xmlWriter->endElement();
    }

    /**
     * Write the metadata in the GPX.
     *
     * @param XMLWriter $xmlWriter The XML writer.
     * @param Workout $workout The workout.
     */
    protected function writeMetaData(XMLWriter $xmlWriter, Workout $workout)
    {
        $xmlWriter->startElement('metadata');
        if ($workout->author() !== null) {
            $xmlWriter->startElement('author');
            $xmlWriter->writeElement('name', $workout->author()->name());
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement();
    }
}
