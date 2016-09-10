<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Extension;

/**
 * Heart rate track point extension.
 */
class HR extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setValue($value)
    {
        if ($value !== null && (!is_int($value) || $value < 0 || $value > 230)) {
            throw new \InvalidArgumentException('The value for the HR must be an integer and between 0 and 230.');
        }

        parent::setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function ID() : string
    {
        return 'HR';
    }

    /**
     * {@inheritdoc}
     */
    public function name() : string
    {
        return 'Heart rate';
    }
}
