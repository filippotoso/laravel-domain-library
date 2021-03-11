<?php

namespace FilippoToso\Domain;

use ReflectionClass;
use ReflectionMethod;

class Subscriber
{
    public function subscribe(Dispatcher $dispatcher)
    {
        $class = new ReflectionClass(self::class);

        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $eventMethods = [
            'retrieved', 'creating', 'created', 'updating', 'updated', 'saving',
            'saved', 'deleting', 'deleted', 'restoring', 'restored', 'replicating'
        ];

        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (in_array($methodName, $eventMethods)) {
                if ($parameter = $method->getParameters()[0] ?? null) {
                    $event = $parameter->getType()->getName();
                    $dispatcher->listen($event, self::class . '@' . $methodName);
                }
            }
        }
    }
}
