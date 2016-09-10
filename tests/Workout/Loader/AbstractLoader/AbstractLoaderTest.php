<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Loader\AbstractLoader;

use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\UnreadableFileException;
use SportTrackerConnector\Core\Workout\Loader\AbstractLoader;

/**
 * Test for \SportTrackerConnector\Core\Workout\Loader\AbstractLoader
 */
class AbstractLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test load from file throws exception if file does not exists.
     */
    public function testLoadFromFileThrowsExceptionIfFileDoesNotExists()
    {
        $filesystem = new Filesystem(new NullAdapter());
        $mock = $this->getMockForAbstractClass(AbstractLoader::class, array($filesystem));

        $file = 'workout.tst';

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('File not found at path: workout.tst');

        /** @var AbstractLoader $mock */
        $mock->fromFile($file);
    }

    /**
     * Test load from file throws exception if file does exists but is not readable.
     */
    public function testLoadFromFileThrowsExceptionIfFileDoesExistsButIsNotReadable()
    {
        $adapter = new class extends NullAdapter
        {
            public function has($path)
            {
                return true;
            }
        };
        $filesystem = new Filesystem($adapter);
        $mock = $this->getMockForAbstractClass(AbstractLoader::class, array($filesystem));

        $file = 'workout.tst';

        $this->expectException(UnreadableFileException::class);

        /** @var AbstractLoader $mock */
        $mock->fromFile($file);
    }

    /**
     * Test that load from file will call load from string.
     */
    public function testDumpToFileCallsDumpToString()
    {
        $adapter = new class extends NullAdapter
        {
            public function has($path)
            {
                return true;
            }

            public function read($path)
            {
                return array('contents' => 'workout data');
            }
        };
        $filesystem = new Filesystem($adapter);
        $mock = $this->getMockBuilder(AbstractLoader::class)
            ->setConstructorArgs(array($filesystem))
            ->setMethods(array('fromString'))
            ->getMockForAbstractClass();

        $mock
            ->expects(self::once())
            ->method('fromString')
            ->with('workout data');

        /** @var AbstractLoader $mock */
        $mock->fromFile('workout.tst');
    }
}
