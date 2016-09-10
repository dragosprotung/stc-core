<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Dumper\GPX;

use DateTime;
use League\Flysystem\FilesystemInterface;
use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Dumper\GPX;
use SportTrackerConnector\Core\Workout\Extension\AbstractExtension;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Test the GPX dumper.
 */
class GPXTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test dumping a workout to a GPX string.
     */
    public function testDumpToStringSingleTrack()
    {
        $workout = new Workout();
        $workout->addTrack(
            new Track(
                array(
                    $this->getTrackPoint(53.551075, 9.993672, '2014-05-30T17:12:58+00:00', 11, 78),
                    $this->getTrackPoint(53.550085, 9.992682, '2014-05-30T17:12:59+00:00', 10, 88)
                ),
                SportMapperInterface::RUNNING
            )
        );
        $workout->setAuthor(
            new Author('John Doe')
        );

        $filesystemMock = $this->createMock(FilesystemInterface::class);
        $gpx = new GPX($filesystemMock);
        $actual = $gpx->dumpToString($workout);

        self::assertXmlStringEqualsXmlFile(__DIR__ . '/Expected/' . $this->getName() . '.gpx', $actual);
    }

    /**
     * Test dumping a workout to a GPX string.
     */
    public function testDumpToStringMultiTrack()
    {
        $workout = new Workout();
        $workout->addTrack(
            new Track(
                array(
                    $this->getTrackPoint(53.551075, 9.993672, '2014-05-30T17:12:58+00:00', 11, 78),
                    $this->getTrackPoint(53.550085, 9.992682, '2014-05-30T17:12:59+00:00', 10, 88)
                ),
                SportMapperInterface::RUNNING
            )
        );
        $workout->addTrack(
            new Track(
                array(
                    $this->getTrackPoint(53.549075, 9.991672, '2014-05-30T17:13:00+00:00', 9, 98),
                    $this->getTrackPoint(53.548085, 9.990682, '2014-05-30T17:13:01+00:00', 8, 108)
                ),
                SportMapperInterface::SWIMMING
            )
        );
        $workout->setAuthor(
            new Author('John Doe')
        );

        $filesystemMock = $this->createMock(FilesystemInterface::class);
        $gpx = new GPX($filesystemMock);
        $actual = $gpx->dumpToString($workout);

        self::assertXmlStringEqualsXmlFile(__DIR__ . '/Expected/' . $this->getName() . '.gpx', $actual);
    }

    /**
     * Test dump unknown extensions throws no error.
     */
    public function testDumpUnknownExtensionsThrowsNoError()
    {
        $genericExtensions = new class extends AbstractExtension
        {
            /**
             * {@inheritdoc}
             */
            public static function ID() : string
            {
                return 'generic-extension';
            }

            /**
             * {@inheritdoc}
             */
            public function name() : string
            {
                return 'generic-extension';
            }
        };
        $workout = new Workout();
        $trackPoint = $this->getTrackPoint(53.551075, 9.993672, '2014-05-30T17:12:58+00:00', 11, 78);
        $trackPoint->addExtension($genericExtensions);
        $workout->addTrack(
            new Track(
                array(
                    $trackPoint
                ),
                SportMapperInterface::RUNNING
            )
        );

        $filesystemMock = $this->createMock(FilesystemInterface::class);
        $gpx = new GPX($filesystemMock);
        $actual = $gpx->dumpToString($workout);

        self::assertXmlStringEqualsXmlFile(__DIR__ . '/Expected/' . $this->getName() . '.gpx', $actual);
    }

    /**
     * Get a track point.
     *
     * @param float $latitude The latitude.
     * @param float $longitude The longitude.
     * @param string $time The time.
     * @param integer $elevation The elevation.
     * @param integer $heartRate The heart rate.
     * @return TrackPoint
     */
    private function getTrackPoint(float $latitude, float $longitude, $time, $elevation, $heartRate = null)
    {
        $trackPoint = new TrackPoint($latitude, $longitude, new DateTime($time));
        $trackPoint->setElevation($elevation);
        if ($heartRate !== null) {
            $trackPoint->setExtensions(array(new HR($heartRate)));
        }

        return $trackPoint;
    }
}
