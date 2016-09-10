<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tracker;

use DateTime;
use DateTimeZone;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use SportTrackerConnector\Core\Workout\SportMapperInterface;

/**
 * Abstract tracker.
 */
abstract class AbstractTracker implements TrackerInterface
{
    use LoggerAwareTrait;

    /**
     * Username for the tracker.
     *
     * @var string
     */
    protected $username;

    /**
     * Password for the tracker.
     *
     * @var string
     */
    protected $password;

    /**
     * The tracker timezone.
     *
     * @var DateTimeZone
     */
    protected $timeZone;

    /**
     * The sport mapper.
     *
     * @var SportMapperInterface
     */
    protected $sportMapper;

    /**
     * @param LoggerInterface $logger The logger.
     * @param DateTimeZone $timeZone
     */
    public function __construct(LoggerInterface $logger, DateTimeZone $timeZone)
    {
        $this->setLogger($logger);
        $this->timeZone = $timeZone;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeZone() : DateTimeZone
    {
        return $this->timeZone;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeZoneOffset() : int
    {
        $originDateTime = new DateTime('now', $this->getTimeZone());

        $utcTimeZone = new DateTimeZone('UTC');
        $utcDateTime = new DateTime('now', $utcTimeZone);

        return $utcTimeZone->getOffset($utcDateTime) - $this->getTimeZone()->getOffset($originDateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function getSportMapper() : SportMapperInterface
    {
        if ($this->sportMapper === null) {
            $this->sportMapper = $this->constructSportMapper();
        }

        return $this->sportMapper;
    }

    /**
     * Construct the sport mapper.
     *
     * @return \SportTrackerConnector\Core\Workout\SportMapperInterface
     */
    abstract protected function constructSportMapper() : SportMapperInterface;
}
