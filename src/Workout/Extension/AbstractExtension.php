<?php

namespace SportTrackerConnector\Core\Workout\Extension;

/**
 * Abstract track point extension.
 */
abstract class AbstractExtension implements ExtensionInterface
{

    const ID = 'GenericExtension';

    /**
     * Name fot the extension.
     *
     * @var string
     */
    protected $name;

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
     * Set the value for the extension.
     *
     * @param mixed $value The value to set.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getID()
    {
        return static::ID;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
}
