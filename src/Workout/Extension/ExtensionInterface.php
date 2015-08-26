<?php

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
    public function getID();

    /**
     * Get the name of the extension.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the value for the extension.
     *
     * @return string|null
     */
    public function getValue();
}
