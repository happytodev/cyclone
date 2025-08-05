<?php

namespace Happytodev\Cyclone\Middlewares;

use Tempest\Http\Request;
use Tempest\Core\Priority;
use Tempest\Http\Response;
use Tempest\Router\HttpMiddleware;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\HttpMiddlewareCallable;

#[Priority(Priority::FRAMEWORK - 9)] // Higher priority than HandleRouteExceptionMiddleware
final readonly class ErrorMiddleware implements HttpMiddleware
{
    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        // Pass the request to the next middleware and retrieve the response
        $response = $next($request);
        if ($response->status->value >= 400 && $response->status->value < 500) {
            return $this->createCustomErrorResponse($response);
        }
        return $response;
    }

    private function createCustomErrorResponse(Response $response): Response
    {
        ll('ErrorMiddleware => response: ' . $response->status->value);
        return new Redirect("/error/{$response->status->value}");
    }
}
