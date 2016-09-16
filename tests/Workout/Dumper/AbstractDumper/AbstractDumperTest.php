<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Dumper\AbstractDumper;

use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\Exception;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use SportTrackerConnector\Core\Workout\Dumper\AbstractDumper;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Test for \SportTrackerConnector\Core\Workout\Dumper\AbstractDumper.
 */
class AbstractDumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test dump to file throws exception if file is not writable.
     */
    public function testDumpToFileThrowsExceptionIfFileIsNotWritable()
    {
        $adapter = new class extends NullAdapter
        {
            public function has($path)
            {
                return true;
            }
        };
        $filesystem = new Filesystem($adapter);
        $mock = $this->getMockForAbstractClass(AbstractDumper::class, array($filesystem));

        $file = 'workout.tst';

        $workoutMock = $this->createMock(Workout::class);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not write to workout.tst');

        /** @var AbstractDumper $mock */
        $mock->toFile($workoutMock, $file);
    }

    /**
     * Test that dump to file will call dump to string.
     */
    public function testDumpToFileCallsDumpToString()
    {
        $workoutMock = $this->createMock(Workout::class);
        $file = 'workout.tst';

        $filesystemMock = $this
            ->getMockBuilder(FilesystemInterface::class)
            ->setMethods(array('put'))
            ->getMockForAbstractClass();
        $filesystemMock
            ->expects(self::once())
            ->method('put')
            ->with($file, 'dumped content')
            ->will(self::returnValue(true));
        $mock = $this->getMockBuilder(AbstractDumper::class)
            ->setConstructorArgs(array($filesystemMock))
            ->setMethods(array('toString'))
            ->getMockForAbstractClass();

        $mock
            ->expects(self::once())
            ->method('toString')
            ->with($workoutMock)
            ->will(self::returnValue('dumped content'));

        /** @var AbstractDumper $mock */
        $mock->toFile($workoutMock, $file);
    }
}
