<?php

namespace SportTrackerConnector\Core\Workout\Extension;

use InvalidArgumentException;

/**
 * Heart rate track point extension.
 */
class HR extends AbstractExtension
{

    const ID = 'HR';

    /**
     * Name for the extension.
     *
     * @var string
     */
    protected $name = 'Heart rate';

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        if ($value !== null && (!is_int($value) || $value < 0 || $value > 230)) {
            throw new InvalidArgumentException('The value for the HR must be an integer and between 0 and 230.');
        }

        parent::setValue($value);
    }
}
