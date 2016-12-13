<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Dumper\JSON;

use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Dumper\JSON;
use SportTrackerConnector\Core\Workout\Extension\HR;
use SportTrackerConnector\Core\Workout\SportMapperInterface;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Test for JSON dumper.
 */
class JSONTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test dumping a workout to a GPX string.
     */
    public function testToStringSingleTrack()
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
        $workout = new Workout([$track], new Author('John Doe'));

        $gpx = new JSON();
        $actual = $gpx->dump($workout);

        self::assertJsonStringEqualsJsonFile(__DIR__ . '/Expected/' . $this->getName() . '.json', $actual);
    }

    /**
     * Test dumping a workout to a GPX string.
     */
    public function testToStringMultiTrack()
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
        $workout = new Workout([$track1, $track2], new Author('John Doe'));

        $gpx = new JSON();
        $actual = $gpx->dump($workout);

        self::assertJsonStringEqualsJsonFile(__DIR__ . '/Expected/' . $this->getName() . '.json', $actual);
    }
}
