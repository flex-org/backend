<?php

namespace App\Http\Middleware\V1;

use App\Modules\V1\Platforms\Services\PlatformService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckDomainExistances
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->header('domain');
        $domainExists = PlatformService::domainExists($domain);
        if (!$domainExists) {
            throw new NotFoundHttpException();
        }

        Config::set('platform.domain', $domain);

        return $next($request);
    }

}
