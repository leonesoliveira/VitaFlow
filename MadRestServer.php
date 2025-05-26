<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: *');
header("Access-Control-Allow-Headers: *");

require_once 'init.php';

use Mad\Rest\Request;
use Mad\Rest\Router;
use Mad\Rest\Response;
use Mad\Rest\RouteServiceProvider;

class MadRestServer
{
    /**
     * Run the REST server
     *
     * @return string JSON response
     */
    public static function run()
    {
        try
        {
            // Boot route service provider to load all routes
            $routeProvider = new RouteServiceProvider();
            $routeProvider->boot();
            
            $request = new Request;

            // Validate the request against registered routes
            $validationResult = Router::validate($request);
            
            // If middleware returned a response, return it
            if ($validationResult !== null) {
                return $validationResult;
            }

            // Execute the matched route
            $response = new Response();
            return $response->json(Router::execute($request));
        }
        catch (Exception $e)
        {
            $response = new Response();
            return $response->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
        catch (Error $e)
        {
            $response = new Response();
            return $response->json(['error' => $e->getMessage()], 500);
        }
    }
}

print MadRestServer::run($_REQUEST);