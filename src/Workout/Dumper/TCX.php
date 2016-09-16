<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Dumper;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use SportTrackerConnector\Core\Workout\Extension\ExtensionInterface;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;
use XMLWriter;

/**
 * Dump a workout to TCX format.
 */
class TCX extends AbstractDumper
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
        $xmlWriter->startElement('TrainingCenterDatabase');

        $xmlWriter->writeAttributeNs(
            'xsi',
            'schemaLocation',
            null,
            'http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2 http://www.garmin.com/xmlschemas/TrainingCenterDatabasev2.xsd'
        );
        $xmlWriter->writeAttribute('xmlns', 'http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2');
        $xmlWriter->writeAttributeNs('xmlns', 'xsi', null, 'http://www.w3.org/2001/XMLSchema-instance');

        $this->writeTracks($xmlWriter, $workout);

        $xmlWriter->endElement();
        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory(true);
    }

    /**
     * Write the tracks to the TCX.
     *
     * @param XMLWriter $xmlWriter The XML writer.
     * @param Workout $workout The workout.
     */
    private function writeTracks(XMLWriter $xmlWriter, Workout $workout)
    {
        $xmlWriter->startElement('Activities');
        foreach ($workout->tracks() as $track) {
            $xmlWriter->startElement('Activity');
            $xmlWriter->writeAttribute('Sport', ucfirst($track->sport()));
            // Use the start date time as the ID. This could be anything.
            $xmlWriter->writeElement('Id', $this->formatDateTime($track->startDateTime()));

            $xmlWriter->startElement('Lap');

            $xmlWriter->writeAttribute('StartTime', $this->formatDateTime($track->startDateTime()));
            $xmlWriter->writeElement('TotalTimeSeconds', (string)$track->duration()->totalSeconds());
            $xmlWriter->writeElement('DistanceMeters', (string)$track->length());

            $this->writeLapHeartRateDate($xmlWriter, $track);

            $xmlWriter->startElement('Track');
            $this->writeTrackPoints($xmlWriter, $track->trackPoints());
            $xmlWriter->endElement();

            $xmlWriter->endElement();

            $xmlWriter->endElement();
        }
        $xmlWriter->endElement();
    }

    /**
     * Write the track points to the TCX.
     *
     * @param XMLWriter $xmlWriter The XML writer.
     * @param TrackPoint[] $trackPoints The track points to write.
     */
    private function writeTrackPoints(XMLWriter $xmlWriter, array $trackPoints)
    {
        foreach ($trackPoints as $trackPoint) {
            $xmlWriter->startElement('Trackpoint');

            // Time of position
            $dateTime = clone $trackPoint->dateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $xmlWriter->writeElement('Time', $this->formatDateTime($dateTime));

            // Position.
            $xmlWriter->startElement('Position');
            $xmlWriter->writeElement('LatitudeDegrees', (string)$trackPoint->latitude());
            $xmlWriter->writeElement('LongitudeDegrees', (string)$trackPoint->longitude());
            $xmlWriter->endElement();

            // Elevation.
            $xmlWriter->writeElement('AltitudeMeters', (string)$trackPoint->getElevation());

            // Distance.
            if ($trackPoint->hasDistance() === true) {
                $xmlWriter->writeElement('DistanceMeters', (string)$trackPoint->getDistance());
            }

            // Extensions.
            $this->writeExtensions($xmlWriter, $trackPoint->extensions());

            $xmlWriter->endElement();
        }
    }

    /**
     * Write the heart rate data for a lap.
     *
     * @param XMLWriter $xmlWriter The XML writer.
     * @param Track $track The track to write.
     */
    protected function writeLapHeartRateDate(XMLWriter $xmlWriter, Track $track)
    {
        $averageHeartRate = array();
        $maxHearRate = null;
        foreach ($track->trackPoints() as $trackPoint) {
            if ($trackPoint->hasExtension(HR::ID()) === true) {
                $pointHearRate = $trackPoint->extension(HR::ID())->value();

                $maxHearRate = max($maxHearRate, $pointHearRate);
                $averageHeartRate[] = $pointHearRate;
            }
        }

        if ($averageHeartRate !== array()) {
            $xmlWriter->startElement('AverageHeartRateBpm');
            $xmlWriter->writeAttributeNs('xsi', 'type', null, 'HeartRateInBeatsPerMinute_t');
            $hearRateValue = array_sum($averageHeartRate) / count($averageHeartRate);
            $xmlWriter->writeElement('Value', (string)$hearRateValue);
            $xmlWriter->endElement();
        }

        if ($maxHearRate !== null) {
            $xmlWriter->startElement('MaximumHeartRateBpm');
            $xmlWriter->writeAttributeNs('xsi', 'type', null, 'HeartRateInBeatsPerMinute_t');
            $xmlWriter->writeElement('Value', (string)$maxHearRate);
            $xmlWriter->endElement();
        }
    }

    /**
     * Write the extensions into the TCX.
     *
     * @param XMLWriter $xmlWriter The XMLWriter.
     * @param ExtensionInterface[] $extensions The extensions to write.
     * @throws InvalidArgumentException If an extension is not known.
     */
    protected function writeExtensions(XMLWriter $xmlWriter, array $extensions)
    {
        foreach ($extensions as $extension) {
            switch ($extension::ID()) {
                case HR::ID():
                    $xmlWriter->startElement('HeartRateBpm');
                    $xmlWriter->writeElement('Value', (string)$extension->value());
                    $xmlWriter->endElement();
                    break;
            }
        }
    }

    /**
     * Format a DateTime object for TCX format.
     *
     * @param DateTime $dateTime The date time to format.
     * @return string
     */
    protected function formatDateTime(DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d\TH:i:s\Z');
    }
}
