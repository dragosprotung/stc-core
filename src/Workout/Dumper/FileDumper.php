<?php

declare(strict_types = 1);

namespace Workout\Dumper;

use Assert\Assertion;
use SportTrackerConnector\Core\Workout\Dumper\DumperInterface;
use SportTrackerConnector\Core\Workout\Workout;

final class FileDumper implements DumperInterface
{
    /**
     * @var DumperInterface
     */
    private $dumper;

    /**
     * @var string
     */
    private $outputFile;

    /**
     * @param DumperInterface $dumper
     * @param string $outputFile
     */
    public function __construct(DumperInterface $dumper, string $outputFile)
    {
        Assertion::writeable($outputFile);

        $this->dumper = $dumper;
        $this->outputFile = $outputFile;
    }

    /**
     * {@inheritdoc}
     */
    public function dump(Workout $workout): string
    {
        $dump = $this->dumper->dump($workout);

        file_put_contents($this->outputFile, $dump);

        return $dump;
    }
}
