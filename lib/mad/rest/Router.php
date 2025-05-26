<?php

namespace Mad\Rest;

class Router
{
    /**
     * Registered routes collection
     * 
     * @var array
     */
    protected static $routes = [];

    /**
     * Current route group URL prefix
     * 
     * @var string
     */
    protected static $urlPrefix = '';

    /**
     * Current route group middleware
     * 
     * @var string|null
     */
    protected static $middlewarePrefix = null;

    /**
     * Create a route group with shared attributes
     * 
     * @param array $parameters Group parameters (prefix, middleware)
     * @param callable|null $callable Callback function to define routes
     * @return void
     */
    public static function group($parameters = [], ?callable $callable = null)
    {
        // Save current prefixes to restore them after the group
        $oldUrlPrefix = static::$urlPrefix;
        $oldMiddlewarePrefix = static::$middlewarePrefix;

        // Set URL prefix if provided
        if (!empty($parameters['prefix'])) {
            $prefix = ltrim($parameters['prefix'], '/');
            static::$urlPrefix = $oldUrlPrefix 
                ? $oldUrlPrefix . '/' . $prefix 
                : '/' . $prefix;
        }

        if (!empty($parameters['middleware'])) {
            static::$middlewarePrefix = $parameters['middleware'];
        }

        // Execute the group callback
        if ($callable) {
            call_user_func($callable);
        }

        // Restore old prefixes
        static::$urlPrefix = $oldUrlPrefix;
        static::$middlewarePrefix = $oldMiddlewarePrefix;
    }

    /**
     * Register a new GET route
     *
     * @param string $uri
     * @param mixed $action
     * @return void
     */
    public static function get($uri, $action)
    {
        static::addRoute('GET', $uri, $action);
    }

    /**
     * Register a new POST route
     *
     * @param string $uri
     * @param mixed $action
     * @return void
     */
    public static function post($uri, $action)
    {
        static::addRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route
     *
     * @param string $uri
     * @param mixed $action
     * @return void
     */
    public static function put($uri, $action)
    {
        static::addRoute('PUT', $uri, $action);
    }

    /**
     * Register a new PATCH route
     *
     * @param string $uri
     * @param mixed $action
     * @return void
     */
    public static function patch($uri, $action)
    {
        static::addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route
     *
     * @param string $uri
     * @param mixed $action
     * @return void
     */
    public static function delete($uri, $action)
    {
        static::addRoute('DELETE', $uri, $action);
    }

    /**
     * Add a route to the routes collection
     *
     * @param string $method HTTP method
     * @param string $uri Route URI
     * @param mixed $action Route action
     * @return void
     */
    protected static function addRoute($method, $uri, $action)
    {
        // Make sure URI starts with a slash
        $uri = '/' . ltrim($uri, '/');
        
        // Apply URL prefix if set
        if (static::$urlPrefix) {
            $fullUri = rtrim(static::$urlPrefix, '/') . $uri;
        } else {
            $fullUri = $uri;
        }
        
        // For debugging
        // echo "Registering route: {$method} {$fullUri}\n";
        
        $route = new Route();
        $route->setMethod($method);
        $route->setUrl($fullUri);
        $route->setAction($action);
        
        if (static::$middlewarePrefix) {
            $route->setMiddleware(static::$middlewarePrefix);
        }
        
        static::$routes[] = $route;
    }
    
    /**
     * Validate the request against registered routes
     * 
     * @param Request $request The request to validate
     * @return mixed Response if middleware returns one, or null to continue
     */
    public static function validate(Request $request)
    {
        $route = static::getRoute($request);
        
        if (!$route) {
            $response = new Response();
            return $response->json(['error' => 'Route not found'], 404);
        }
        
        if (!empty($route->getMiddleware())) {
            $middleware = $route->getMiddleware();
            
            // Check if middleware is a string (class name)
            if (is_string($middleware)) {
                // Check if middleware is a fully qualified class name
                if (strpos($middleware, '\\') === false) {
                    $middlewareClass = $middleware;
                } else {
                    $middlewareClass = $middleware;
                }
                
                if (!class_exists($middlewareClass)) {
                    $response = new Response();
                    return $response->json(['error' => 'Middleware not found: ' . $middlewareClass], 500);
                }
                
                $middlewareInstance = new $middlewareClass();
                
                // Create a simple next closure
                $next = function ($request) {
                    return null;
                };
                
                $result = $middlewareInstance->handle($request, $next);
                
                // If middleware returns a response, return it immediately
                if ($result instanceof ResponseInterface) {
                    return $result;
                }
            } elseif (is_callable($middleware)) {
                // Callable middleware
                $result = call_user_func($middleware, $request, function ($request) {
                    return null;
                });
                
                // If middleware returns a response, return it immediately
                if ($result instanceof ResponseInterface) {
                    return $result;
                }
            }
        }
        
        return null; // Validation passed
    }
    
    /**
     * Get matching route for the request
     *
     * @param Request $request
     * @return Route|null
     */
    public static function getRoute(Request $request)
    {
        $url = $request->getUrl();
        $method = $request->getMethod();
        
        // Debug information
        // Uncomment for debugging
        // var_dump("Looking for route: {$method} {$url}");
        // var_dump("Available routes:", array_map(function($route) {
        //     return $route->getMethod() . ' ' . $route->getUrl();
        // }, static::$routes));
        
        foreach (static::$routes as $route) {
            if ($route->getMethod() == $method && $route->matchUrl($url)) {
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Execute the matched route
     *
     * @param Request $request
     * @return mixed
     */
    public static function execute(Request $request)
    {
        $route = static::getRoute($request);
        
        if (!$route) {
            $response = new Response();
            return $response->json(['error' => 'Route not found'], 404);
        }
        
        $action = $route->getAction();
        
        if (is_callable($action)) {
            return call_user_func($action, $request);
        }
        
        if (is_string($action) && strpos($action, '::') !== false) {
            list($controller, $method) = explode('::', $action);
            $instance = new $controller();
            return call_user_func([$instance, $method], $request);
        }
        
        $response = new Response();
        return $response->json(['error' => 'Invalid route action'], 500);
    }
}