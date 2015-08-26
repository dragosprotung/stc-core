<?php

namespace SportTrackerConnector\Core\Workout\Loader;

use InvalidArgumentException;

/**
 * Abstract loader.
 */
abstract class AbstractLoader implements LoaderInterface
{

    /**
     * {@inheritdoc}
     */
    public function fromFile($file)
    {
        if (is_readable($file) !== true) {
            throw new InvalidArgumentException('File "' . $file . '" is not readable.');
        }

        return $this->fromString(file_get_contents($file));
    }
}
