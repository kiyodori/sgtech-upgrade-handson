<?php
namespace App;

use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    public function bootstrap()
    {
        parent::bootstrap();
    }

    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this));

        return $middlewareQueue;
    }
}
