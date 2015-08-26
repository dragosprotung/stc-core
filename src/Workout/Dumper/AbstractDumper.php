<?php

namespace SportTrackerConnector\Core\Workout\Dumper;

use SportTrackerConnector\Core\Workout\Workout;
use InvalidArgumentException;

/**
 * Abstract class for dumpers.
 */
abstract class AbstractDumper implements DumperInterface
{
    /**
     * {@inheritdoc}
     */
    public function dumpToFile(Workout $workout, $outputFile, $overwrite = true)
    {
        if (file_exists($outputFile) !== true && is_writable(dirname($outputFile)) !== true) {
            throw new InvalidArgumentException('Directory for output file "' . $outputFile . '" is not writable.');
        } elseif ($overwrite === true && file_exists($outputFile) && is_writable($outputFile) !== true) {
            throw new InvalidArgumentException('The output file "' . $outputFile . '" is not writable.');
        }

        return file_put_contents($outputFile, $this->dumpToString($workout)) !== false;
    }
}
