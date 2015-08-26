<?php

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
    abstract protected function getMap();

    /**
     * {@inheritdoc}
     */
    public function getSportFromCode($code)
    {
        $code = strtolower($code);
        $codes = array_flip(static::getMap());
        if (isset($codes[$code])) {
            return $codes[$code];
        }

        return self::OTHER;
    }

    /**
     * {@inheritdoc}
     */
    public function getCodeFromSport($sport)
    {
        $sport = strtolower($sport);
        $codes = static::getMap();
        if (isset($codes[$sport])) {
            return $codes[$sport];
        } elseif (isset($codes[self::OTHER])) {
            return $codes[self::OTHER];
        }

        return null;
    }
}
