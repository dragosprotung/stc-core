<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Dumper;

use League\Flysystem\Exception;
use League\Flysystem\FilesystemInterface;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Abstract class for dumpers.
 */
abstract class AbstractDumper implements DumperInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function toFile(Workout $workout, string $outputFile) : bool
    {
        $return = $this->filesystem->put($outputFile, $this->toString($workout));
        if ($return !== true) {
            throw new Exception(sprintf('Could not write to %s', $outputFile));
        }

        return true;
    }
}
