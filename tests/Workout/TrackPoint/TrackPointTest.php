<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\TrackPoint;

use SportTrackerConnector\Core\Workout\TrackPoint;

/**
 * Test for a workout track point.
 */
class TrackPointTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testDistance().
     *
     * @return array
     */
    public function dataProviderTestDistance()
    {
        return array(
            array(TrackPoint::with(-38.691450, 176.079795, new \DateTimeImmutable('2014-06-01 00:00:0')), 0),
            array(TrackPoint::with(-38.714081, 176.084209, new \DateTimeImmutable()), 2545.436281548, 0.000001),
            array(TrackPoint::with(-38.723081, 176.079209, new \DateTimeImmutable()), 3517.57425628, 0.000001),
            array(TrackPoint::with(-38.6914501, 176.0797951, new \DateTimeImmutable()), 0.01410562, 0.000001),
            array(TrackPoint::with(0.0, 0.0, new \DateTimeImmutable()), 15694215.397435, 0.000001)
        );
    }

    /**
     * Test distance calculation between 2 points.
     *
     * @dataProvider dataProviderTestDistance
     * @param TrackPoint $destination The destination track point.
     * @param float $expected The expected distance.
     * @param float $delta The allowed numerical distance between two values to consider them equal
     */
    public function testDistance(TrackPoint $destination, $expected, $delta = 0.0)
    {
        $starPoint = TrackPoint::with(-38.691450, 176.079795, new \DateTimeImmutable());

        $actual = $starPoint->distanceFromPoint($destination);
        self::assertEquals($expected, $actual, '', $delta);
    }

    /**
     * Data provider for testSpeedForPointsWithoutDistance().
     *
     * @return array
     */
    public function dataProviderTestSpeedForPointsWithoutDistance()
    {
        return array(
            array(TrackPoint::with(-38.691450, 176.079795, new \DateTimeImmutable('2014-06-01 00:00:00')), 0),
            array(TrackPoint::with(-38.691450, 176.079795, new \DateTimeImmutable('2014-06-01 00:00:01')), 0),
            array(
                TrackPoint::with(-38.714081, 176.084209, new \DateTimeImmutable('2014-06-01 00:00:10')),
                916.357061357,
                0.000000001
            ),
            array(
                TrackPoint::with(-38.723081, 176.079209, new \DateTimeImmutable('2014-06-01 00:01:00')),
                211.05445537715
            ),
            array(
                TrackPoint::with(-38.723081, 176.079209, new \DateTimeImmutable('2014-06-01 00:02:00')),
                105.52722768857
            ),
            array(
                TrackPoint::with(-38.6914501, 176.0797951, new \DateTimeImmutable('2014-06-01 00:05:00')),
                0.00016926749741346
            ),
            array(TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('2014-06-01 22:00:00')), 713.37342715616)
        );
    }

    /**
     * Test speed calculation between 2 points that do not have the distance set.
     *
     * @dataProvider dataProviderTestSpeedForPointsWithoutDistance
     * @param TrackPoint $destination The destination track point.
     * @param float $expected The expected speed.
     * @param float $delta The allowed numerical distance between two values to consider them equal
     */
    public function testSpeedForPointsWithoutDistance(TrackPoint $destination, $expected, $delta = 0.0)
    {
        $startPoint = TrackPoint::with(-38.691450, 176.079795, new \DateTimeImmutable('2014-06-01 00:00:00'));

        $actual = $startPoint->speed($destination);
        self::assertEquals($expected, $actual, '', $delta);
    }

    /**
     * Test get extension throws exception if extension not found.
     */
    public function testGetExtensionThrowsExceptionIfExtensionNotFound()
    {
        $trackPoint = TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('now'));

        $name = 'non-existing-extension';

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Extension "' . $name . '" not found.');

        $trackPoint->extension($name);
    }
}
