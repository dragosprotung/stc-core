<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Loader\GPX;

use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\Loader\GPX;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

class GPXTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test loading a workout from a GPX string with a single track.
     */
    public function testFromStringSingleTrack()
    {
        $track = new Track(
            array(
                TrackPoint::with(
                    53.551075,
                    9.993672,
                    new \DateTimeImmutable('2014-05-30T17:12:58+00:00'),
                    11.0,
                    [HR::fromValue(78)]
                ),
                TrackPoint::with(
                    53.550085,
                    9.992682,
                    new \DateTimeImmutable('2014-05-30T17:12:59+00:00'),
                    10.0,
                    [HR::fromValue(88)]
                )
            ),
            SportMapperInterface::RUNNING
        );
        $expected = new Workout([$track], new Author('John Doe'));

        $gpx = new GPX();
        $actual = $gpx->load(file_get_contents(__DIR__ . '/Fixtures/testFromStringSingleTrack.gpx'));

        self::assertEquals($expected, $actual);
    }

    /**
     * Test loading a workout from a GPX string with multiple tracks.
     */
    public function testFromStringMultiTrack()
    {
        $track1 = new Track(
            array(
                TrackPoint::with(
                    53.551075,
                    9.993672,
                    new \DateTimeImmutable('2014-05-30T17:12:58+00:00'),
                    11.0,
                    [HR::fromValue(78)]
                ),
                TrackPoint::with(
                    53.550085,
                    9.992682,
                    new \DateTimeImmutable('2014-05-30T17:12:59+00:00'),
                    10.0,
                    [HR::fromValue(88)]
                )
            ),
            SportMapperInterface::RUNNING
        );
        $track2 = new Track(
            array(
                TrackPoint::with(
                    53.549075,
                    9.991672,
                    new \DateTimeImmutable('2014-05-30T17:13:00+00:00'),
                    9.0,
                    [HR::fromValue(98)]
                ),
                TrackPoint::with(
                    53.548085,
                    9.990682,
                    new \DateTimeImmutable('2014-05-30T17:13:01+00:00'),
                    8.0,
                    [HR::fromValue(108)]
                )
            ),
            SportMapperInterface::SWIMMING
        );
        $expected = new Workout([$track1, $track2], new Author('John Doe'));

        $gpx = new GPX();
        $actual = $gpx->load(file_get_contents(__DIR__ . '/Fixtures/testFromStringMultiTrack.gpx'));

        self::assertEquals($expected, $actual);
    }
}
