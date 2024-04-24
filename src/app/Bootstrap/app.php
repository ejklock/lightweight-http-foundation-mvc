<?php

namespace Bootstrap;

use App\Routes\RouteManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    public static function make()
    {
        $request = Request::createFromGlobals();

        $response = new Response();

        $kernel = new class($request, $response)
        {
            private $request;
            private $response;
            private $routes;

            public function __construct(Request $request, Response $response)
            {
                $this->request = $request;
                $this->response = $response;
                $this->routes = RouteManager::getRoutes();
            }

            protected function createResponseFromControllerResult($result)
            {

                if ($result instanceof Response || $result instanceof RedirectResponse) {
                    return $result;
                }
                if (is_string($result)) {
                    $this->response->setContent($result);
                }
                if (is_array($result) || is_object($result)) {
                    $this->response->setContent(json_encode($result));
                }

                return $this->response->prepare($this->request);
            }

            protected function currentRequestMethodIsAllowedForRoute($routeMethod)
            {
                return $this->request->getMethod() === $routeMethod;
            }
            protected function getRouteHandlerForRequest(Request $request)
            {
                $matchedRoute = array_values(array_filter($this->routes, function ($route) use ($request) {
                    return $request->getPathInfo() == $route['path'] && $request->getMethod() == $route['method'];
                }));

                if (empty($matchedRoute)) {
                    return 'App\Handlers\HttpHandlers::notFound';
                }
                if (count($matchedRoute) === 1 && !$this->currentRequestMethodIsAllowedForRoute($matchedRoute[0]['method'])) {

                    return 'App\Handlers\HttpHandlers::notAllowed';
                }

                return count($matchedRoute) == 1 ? reset($matchedRoute)['handler'] : 'App\Handlers\HttpHandlers::notFound';
            }
            public function handle()
            {
                try {

                    [$controller, $method] = explode('::', $this->getRouteHandlerForRequest($this->request));

                    $controller = new $controller();
                    $controller->$method($this->request, $this->response);

                    return  $this->createResponseFromControllerResult(call_user_func(
                        [$controller, $method],
                        $this->request,
                        $this->response
                    ));
                } catch (\Throwable $th) {
                    throw new \RuntimeException('Something went wrong', 0, $th);
                }
            }
        };

        return $kernel->handle()->send();
    }
}
