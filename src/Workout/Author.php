<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

/**
 * Author of a workout.
 */
class Author
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
    public function __construct($name)
    {
        if (is_string($name) || (is_object($name) && method_exists($name, '__toString'))) {
            $name = (string)$name;
        } else {
            throw new \InvalidArgumentException('The name of the author must be a string.');
        }

        $this->name = $name;
    }

    /**
     * Get the name of the author.
     *
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }
}
