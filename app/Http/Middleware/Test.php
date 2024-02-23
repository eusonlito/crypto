<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\NotFoundException;

class Test
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('test.enabled') !== true) {
            throw new NotFoundException();
        }

        return $next($request);
    }
}
