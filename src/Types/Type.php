<?php

namespace FilippoToso\Domain\Types;

class Type
{
    /**
     * Staticalli crate a Type
     *
     * @param mixed ...$args
     * @return void
     */
    public static function from(...$args)
    {
        return new static(...$args);
    }
}
