<?php

namespace Inertia\Tests;

use Inertia\Response;
use Inertia\ResponseFactory;

class ComposerTest extends TestCase
{
    public function test_composer_is_called_before_render(): void
    {
        $responseFactory = new ResponseFactory();
        $responseFactory->composer('Foo/Bar', function (Response $response) {
            $response->with('fruits', ['apple', 'orange']);
        });
        $expectedProps = [
            'fruits' => ['apple', 'orange'],
        ];

        $response = $responseFactory->render('Foo/Bar');

        $this->assertEquals($expectedProps, $response->props());
    }

    public function test_multiple_composers()
    {
        $responseFactory = new ResponseFactory();
        $responseFactory->composer('Foo/Bar', function (Response $response) {
            $response->with('fruits', ['apple', 'orange']);
        });
        $responseFactory->composer('Foo/Bar', function (Response $response) {
            $response->with('colors', ['red', 'blue']);
        });
        $expectedProps = [
            'fruits' => ['apple', 'orange'],
            'colors' => ['red', 'blue'],
        ];

        $response = $responseFactory->render('Foo/Bar');

        $this->assertEquals($expectedProps, $response->props());
    }

    public function test_composer_multiple_components()
    {
        $responseFactory = new ResponseFactory();
        $responseFactory->composer(['Foo/Bar', 'Foo/Baz'], function (Response $response) {
            $response->with('fruits', ['apple', 'orange']);
        });
        $expectedProps = [
            'fruits' => ['apple', 'orange'],
        ];

        $response1 = $responseFactory->render('Foo/Bar');
        $response2 = $responseFactory->render('Foo/Bar');

        $this->assertEquals($expectedProps, $response1->props());
        $this->assertEquals($expectedProps, $response2->props());
    }
}
