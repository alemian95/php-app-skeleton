<?php

namespace Src\Components;

abstract class Facade {

    /** @var \Psr\Container\ContainerInterface */
    protected static \Psr\Container\ContainerInterface $container;

    /**
     * Set the dependency injection container.
     */
    public static function setContainer(\Psr\Container\ContainerInterface $container): void {
        self::$container = $container;
    }

    /**
     * Retrieve the underlying instance from the container.
     */
    protected static function getInstance() {
        if (!isset(self::$container)) {
            throw new \RuntimeException('Container not set in Facade.');
        }
        return self::$container->get(static::getFacadeAccessor());
    }

    /**
     * Handle dynamic, static calls to the underlying instance.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args) {
        $instance = static::getInstance();
        if (!method_exists($instance, $method)) {
            throw new \BadMethodCallException("Method {$method} does not exist in " . get_class($instance));
        }
        return $instance->$method(...$args);
    }

    /**
     * Return the container binding key.
     */
    abstract protected static function getFacadeAccessor(): string;
}