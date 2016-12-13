<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Extension;

use Assert\Assertion;

/**
 * Heart rate track point extension.
 */
final class HR implements ExtensionInterface
{
    /**
     * Value of the heart rate.
     *
     * @var int
     */
    private $value;

    /**
     * @param int $value
     */
    private function __construct(?int $value)
    {
        Assertion::greaterOrEqualThan($value, 40);
        Assertion::lessOrEqualThan($value, 210);

        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return HR
     */
    public static function fromValue($value): HR
    {
        Assertion::integerish($value);

        return new self((int)$value);
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public static function ID(): string
    {
        return 'HR';
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'Heart rate';
    }
}
