<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Workout\Extension;

/**
 * Interface for all track point extensions.
 */
interface ExtensionInterface
{
    /**
     * Get the ID of the extension.
     *
     * @return string
     */
    public static function ID() : string;

    /**
     * Get the name of the extension.
     *
     * @return string
     */
    public function name() : string;

    /**
     * Get the value for the extension.
     *
     * @return string|null
     */
    public function value();
}
