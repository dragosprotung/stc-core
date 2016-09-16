<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tracker;

use SportTrackerConnector\Core\Workout\SportMapperInterface;

/**
 * Abstract tracker.
 */
abstract class AbstractTracker implements TrackerInterface
{
    /**
     * The sport mapper.
     *
     * @var SportMapperInterface
     */
    protected $sportMapper;

    /**
     * {@inheritdoc}
     */
    public function sportMapper() : SportMapperInterface
    {
        if ($this->sportMapper === null) {
            $this->sportMapper = $this->constructSportMapper();
        }

        return $this->sportMapper;
    }

    /**
     * Construct the sport mapper.
     *
     * @return SportMapperInterface
     */
    abstract protected function constructSportMapper() : SportMapperInterface;
}
