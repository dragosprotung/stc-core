<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout;

/**
 * Abstract class for tracker sport definitions.
 */
abstract class AbstractSportMapper implements SportMapperInterface
{
    /**
     * Get the map between the tracker's sport codes and internal sport codes.
     *
     * The key should be the internal sport code.
     *
     * @return array
     */
    abstract protected function getMap() : array;

    /**
     * {@inheritdoc}
     */
    public function sportFromCode($code) : string
    {
        $code = strtolower($code);
        $codes = array_flip(static::getMap());

        return $codes[$code] ?? self::OTHER;
    }

    /**
     * {@inheritdoc}
     */
    public function codeFromSport($sport) : string
    {
        $sport = strtolower($sport);
        $codes = static::getMap();

        if (array_key_exists($sport, $codes)) {
            return $codes[$sport];
        } elseif (array_key_exists(self::OTHER, $codes)) {
            return $codes[self::OTHER];
        }

        throw new \RuntimeException('Sport not mapped.');
    }
}
