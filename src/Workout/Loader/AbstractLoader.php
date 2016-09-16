<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Loader;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\UnreadableFileException;
use SportTrackerConnector\Core\Workout\Workout;

/**
 * Abstract loader.
 */
abstract class AbstractLoader implements LoaderInterface
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
    public function fromFile($file) : Workout
    {
        $content = $this->filesystem->read($file);
        if ($content === false) {
            throw new UnreadableFileException();
        }

        return $this->fromString($content);
    }
}
