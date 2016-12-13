<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

use Assert\Assertion;

/**
 * Author of a workout.
 */
final class Author
{
    /**
     * The name of the author.
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param string $name The name of the author.
     */
    public function __construct(string $name)
    {
        Assertion::string($name);
        Assertion::notBlank($name);

        $this->name = $name;
    }

    /**
     * Get the name of the author.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
