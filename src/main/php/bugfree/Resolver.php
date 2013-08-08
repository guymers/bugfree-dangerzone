<?php

namespace bugfree;


interface Resolver
{
    /**
     * Determines if a given string can resolve correctly.
     *
     * @param string $name A fully qualified class name or namespace name.
     *
     * @return boolean true if the object exists within the source tree, the libraries or PHP.
     */
    public function isValid($name);

    const _CLASS = __CLASS__;
}
