<?php

declare(strict_types = 1);

namespace Workout\Loader;

use Assert\Assertion;
use SportTrackerConnector\Core\Workout\Loader\GPX;
use SportTrackerConnector\Core\Workout\Loader\LoaderInterface;
use SportTrackerConnector\Core\Workout\Loader\TCX;
use SportTrackerConnector\Core\Workout\Workout;

class File implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(string $filePath): Workout
    {
        Assertion::file($filePath);
        Assertion::readable($filePath);

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'gpx':
                $loader = new GPX();
                break;
            case 'tcx':
                $loader = new TCX();
                break;
            default:
                throw new \RuntimeException(sprintf('No loader for file "%s"', $filePath));
        }

        return $loader->load(file_get_contents($filePath));
    }
}
