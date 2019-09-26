<?php

namespace Inertia;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setRootView($name)
 * @method static void share($key, $value = null)
 * @method static array getShared($key = null)
 * @method static array composer($component, $callback)
 * @method static void version($version)
 * @method static int|string getVersion()
 * @method static \Inertia\Response render($component, $props = [])
 *
 * @see \Inertia\ResponseFactory
 */
class Inertia extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ResponseFactory::class;
    }
}
