<?php

namespace Inertia;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class ResponseFactory
{
    protected $rootView = 'app';
    protected $sharedProps = [];
    protected $composers = [];
    protected $version = null;

    public function setRootView($name)
    {
        $this->rootView = $name;
    }

    public function share($key, $value = null)
    {
        if (is_array($key)) {
            $this->sharedProps = array_merge($this->sharedProps, $key);
        } else {
            Arr::set($this->sharedProps, $key, $value);
        }
    }

    public function getShared($key = null)
    {
        if ($key) {
            return Arr::get($this->sharedProps, $key);
        }

        return $this->sharedProps;
    }

    public function composer($components, Closure $callback)
    {
        foreach ((array) $components as $component) {
            $this->composers[] = [
                'component' => $component,
                'callback' => $callback,
            ];
        }

        return $this;
    }

    protected function callComposers($component, Response $response)
    {
        collect($this->composers)
            ->filter(function (array $composer) use ($component) {
                return $component === $composer['component'];
            })
            ->each(function (array $composer) use ($response) {
                $composer['callback']($response);
            });
    }

    public function version($version)
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version instanceof Closure ? App::call($this->version) : $this->version;
    }

    public function render($component, $props = [])
    {
        if ($props instanceof Arrayable) {
            $props = $props->toArray();
        }

        $response = new Response(
            $component,
            array_merge($this->sharedProps, $props),
            $this->rootView,
            $this->getVersion()
        );

        $this->callComposers($component, $response);

        return $response;
    }
}
