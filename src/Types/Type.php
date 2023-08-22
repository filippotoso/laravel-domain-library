<?php

namespace FilippoToso\Domain\Types;

class Type
{
    /**
     * Statically crate a Type
     *
     * @param mixed ...$args
     * @return static
     */
    public static function from(...$args)
    {
        return new static(...$args);
    }
}
