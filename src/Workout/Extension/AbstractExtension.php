<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Extension;

/**
 * Abstract track point extension.
 */
abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * Value of the extension.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param mixed $value The value for the extension.
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * @param mixed $value The value to set.
     *
     * @throws \InvalidArgumentException
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->value;
    }
}
