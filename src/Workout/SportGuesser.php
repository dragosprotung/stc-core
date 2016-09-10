<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

/**
 * Simple class that tries to guess the sport from text representation.
 */
class SportGuesser
{
    /**
     * Get the sport code from the tracker sport code.
     *
     * @param string $code The code from the tracker.
     * @return string
     */
    public static function getSportFromCode(string $code) : string
    {
        switch (strtolower(trim($code))) {
            case SportMapperInterface::RUNNING:
            case 'run':
                return SportMapperInterface::RUNNING;
            case SportMapperInterface::CYCLING_SPORT:
            case 'cycling':
                return SportMapperInterface::CYCLING_SPORT;
            case SportMapperInterface::CYCLING_TRANSPORT:
                return SportMapperInterface::CYCLING_TRANSPORT;
            case SportMapperInterface::SWIMMING:
                return SportMapperInterface::SWIMMING;
            default:
                return SportMapperInterface::OTHER;
        }
    }
}
