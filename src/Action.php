<?php

namespace FilippoToso\Domain;

abstract class Action
{
    /**
     * Execute the current action.
     *
     * @return void
     */
    abstract public function execute();
}
