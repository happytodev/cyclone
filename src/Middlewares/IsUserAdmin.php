<?php

namespace Happytodev\Cyclone\Middlewares;

use App\Auth\User;
use Tempest\Log\Logger;
use Tempest\Http\Request;
use Tempest\Core\Priority;
use Tempest\Http\Response;
use Tempest\Auth\Authenticator;
use Tempest\Router\HttpMiddleware;
use Tempest\Discovery\SkipDiscovery;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\HttpMiddlewareCallable;
use Happytodev\Cyclone\Controllers\AdminController;
use Happytodev\Cyclone\Controllers\ErrorController;

use function Tempest\uri;

#[SkipDiscovery]
#[Priority(Priority::HIGHEST - 1)]
final class IsUserAdmin implements HttpMiddleware
{
    public function __construct(
        private Authenticator $authenticator,
        private Logger $logger,
    ) {
    }

    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        ll('current user', $this->authenticator->currentUser());

        // Check if a user is logged in
        if (!$this->authenticator->currentUser()) {
            $this->logger->info("User not connected");
            return new Redirect(uri([AdminController::class, 'showLogin']));
        }

        $isAdmin = $this->authenticator->currentUser()->hasPermission('admin');

        if (!$isAdmin) {
            $this->logger->info("User not admin");
            // redirect to error 403 page
            // return new Redirect(uri([ErrorController::class, 'error'], ['error' => '403']));
            return new Redirect(uri('/error/403'));
        }

        $this->logger->info("User has role admin");

        // If the user is logged in, continue
        return $next($request);
    }
}
