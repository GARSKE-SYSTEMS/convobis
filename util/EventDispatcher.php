<?php

namespace Convobis\Util;

class EventDispatcher
{
    private static array $listeners = [];

    /**
     * Subscribe a listener to an event
     * @param string $event
     * @param callable $listener
     */
    public static function subscribe(string $event, callable $listener): void
    {
        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }
        self::$listeners[$event][] = $listener;
    }

    /**
     * Dispatch an event to all subscribers
     * @param string $event
     * @param mixed $payload
     */
    public static function dispatch(string $event, $payload = null): void
    {
        if (isset(self::$listeners[$event])) {
            foreach (self::$listeners[$event] as $listener) {
                call_user_func($listener, $payload);
            }
        }
    }
}
