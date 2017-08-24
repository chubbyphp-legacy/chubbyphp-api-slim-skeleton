<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\ApiHttp;

use Chubbyphp\ApiHttp\Factory\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

final class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @param int $code
     *
     * @return ResponseInterface
     */
    public function createResponse($code = 200): ResponseInterface
    {
        return new Response($code);
    }
}
