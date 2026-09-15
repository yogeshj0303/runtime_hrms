<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Business;

class ActiveBusinessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->active_business_id) {

            $activeBusiness = Business::find(
                $user->active_business_id
            );

            view()->share('activeBusiness', $activeBusiness);
        }

        return $next($request);
    }
}