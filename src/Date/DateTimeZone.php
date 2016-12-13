<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Date;

/**
 * DateTimeZone helper class.
 */
final class DateTimeZone extends \DateTimeZone
{
    /**
     * Get offset between a time zone and UTC time zone in seconds.
     *
     * @return integer
     */
    public function UTCTimeZoneOffset() : int
    {
        $originDateTime = new \DateTime('now', $this);

        $utcTimeZone = new \DateTimeZone('UTC');
        $utcDateTime = new \DateTime('now', $utcTimeZone);

        return $utcTimeZone->getOffset($utcDateTime) - $this->getOffset($originDateTime);
    }
}
