<?php

namespace Happytodev\Cyclone\Middlewares;

use Tempest\Core\Priority;
use Tempest\Discovery\SkipDiscovery;
use Happytodev\Cyclone\Controllers\AdminController;
use Tempest\Http\Responses\Redirect;
use Tempest\Log\Logger;
use Tempest\Auth\Authenticator;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Router\HttpMiddleware;
use Tempest\Router\HttpMiddlewareCallable;

use function Tempest\uri;

#[SkipDiscovery]
#[Priority(Priority::HIGHEST)]
final class IsUserConnected implements HttpMiddleware
{
    // List of public routes that do not require checking
    private array $publicRoutes = [
        // '/admin/login',
        // '/auth/github',
        // '/auth/github/redirect',
        // '/auth/github/callback',
        // '/auth/amazon',
        // '/auth/amazon/redirect',
        // '/auth/amazon/callback'
    ];

    public function __construct(
        private Authenticator $authenticator,
        private Logger $logger,
    ) {
    }


    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        // If the current route is public, go directly to the next manager
        // if (in_array($request->path, $this->publicRoutes)) {
        //     ll('request path is :' . $request->path);
        //     return $next($request);
        // }

        ll('current user', $this->authenticator->currentUser());

        // Check if a user is logged in
        if (!$this->authenticator->currentUser()) {
            $this->logger->info("User not connected");
            return new Redirect(uri([AdminController::class, 'showLogin']));
        }

        // If the user is logged in, continue
        return $next($request);
    }
}
