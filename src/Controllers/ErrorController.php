<?php

namespace Happytodev\Cyclone\Controllers;

use Tempest\View\View;
use Tempest\Router\Get;

use function Tempest\view;
use Tempest\Http\Response;
use Tempest\Log\Logger;

class ErrorController

{
    public function __construct(
        private Logger $logger,
    ) {}

    #[Get('/error/{error}')]
    public function error(string $error)
    {
        if (!in_array($error, ['403', '404'])) {
            $this->logger->error("Error not found : $error");
            throw new \Exception("Error not found");
        }

        return match ($error) {
            '403' => $this->error403(),
            '404' => $this->error404(),
            default => $this->defaultError()
        };

    }

    protected function error403(): View
    {
        $message = "You are not allowed to access this page...";
        return view(
            '../Views/Errors/403.view.php',
            title: "Access issue",
            status: 403,
            message: $message
        );
    }

    protected function error404(): View
    {
        $message = "The page you are looking for does not exist...";
        return view(
            '../Views/Errors/404.view.php',
            title: "Are you lost?",
            status: 404,
            message: $message
        );
    }
}
