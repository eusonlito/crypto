<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Request;

use App\Services\Platform\Request\RequestAbstract as RequestAbstractPlatform;

abstract class RequestAbstract extends RequestAbstractPlatform
{
    /**
     * @var string
     */
    protected string $cacheName = 'kucoin';
}
