<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Dumper\TCX;

use DateTime;
use League\Flysystem\FilesystemInterface;
use SportTrackerConnector\Core\Workout\Dumper\TCX;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Test the TCX dumper.
 */
class TCXTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test dumping a workout to a TCX string.
     */
    public function testToStringSingleActivity()
    {
        $workout = new Workout();
        $workout->addTrack(
            new Track(
                array(
                    $this->getTrackPoint(53.551075, 9.993672, '2014-05-30T17:12:58+00:00', 11, 0.0, 78),
                    $this->getTrackPoint(53.550085, 9.992682, '2014-05-30T17:13:00+00:00', 10, 128.0, 88)
                ),
                SportMapperInterface::RUNNING
            )
        );

        $filesystemMock = $this->createMock(FilesystemInterface::class);
        $tcx = new TCX($filesystemMock);
        $actual = $tcx->toString($workout);

        self::assertXmlStringEqualsXmlFile(__DIR__ . '/Expected/' . $this->getName() . '.tcx', $actual);
    }

    /**
     * Test dumping a workout to a TCX string.
     */
    public function testToStringMultiActivity()
    {
        $workout = new Workout();
        $workout->addTrack(
            new Track(
                array(
                    $this->getTrackPoint(53.551075, 9.993672, '2014-05-30T17:12:58+00:00', 11, null, 78),
                    $this->getTrackPoint(53.550085, 9.992682, '2014-05-30T17:12:59+00:00', 10, 128.0, 88)
                ),
                SportMapperInterface::RUNNING
            )
        );
        $workout->addTrack(
            new Track(
                array(
                    $this->getTrackPoint(53.549075, 9.991672, '2014-05-30T17:13:00+00:00', 9, 258.0, 98),
                    $this->getTrackPoint(53.548085, 9.990682, '2014-05-30T17:13:01+00:00', 8, 456.0, 108)
                ),
                SportMapperInterface::SWIMMING
            )
        );

        $filesystemMock = $this->createMock(FilesystemInterface::class);
        $tcx = new TCX($filesystemMock);
        $actual = $tcx->toString($workout);

        self::assertXmlStringEqualsXmlFile(__DIR__ . '/Expected/' . $this->getName() . '.tcx', $actual);
    }

    /**
     * Get a track point.
     *
     * @param float $latitude The latitude.
     * @param float $longitude The longitude.
     * @param string $time The time.
     * @param float $distance The distance from start to that point.
     * @param integer $elevation The elevation.
     * @param integer $heartRate The heart rate.
     * @return TrackPoint
     */
    private function getTrackPoint(
        float $latitude,
        float $longitude,
        $time,
        $elevation,
        $distance = null,
        $heartRate = null
    ) {
        $trackPoint = new TrackPoint($latitude, $longitude, new DateTime($time));
        $trackPoint->setElevation($elevation);
        $trackPoint->setDistance($distance);
        if ($heartRate !== null) {
            $trackPoint->setExtensions(array(new HR($heartRate)));
        }

        return $trackPoint;
    }
}
