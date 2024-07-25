<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterToken {

    protected $expiration = 40;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($token = static::getTokenFromRequest($request)) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            if( !$this->isValidAccessToken($accessToken) ) {
                throw new \Exception(
                    "The token expired.",
                    401,
                );
            }

        }
        return $next($request);
    }

    public static function getTokenFromRequest(Request $request)
    {
        if( !$request->hasHeader('Token') ) {
            return null;
        }

        $token = $request->header('Token');
        return $token;
    }

        /**
     * Determine if the provided access token is valid.
     *
     * @param  mixed  $accessToken
     * @return bool
     */
    protected function isValidAccessToken($accessToken): bool
    {
        if (! $accessToken) {
            return false;
        }

        $isValid =
            (! $this->expiration || $accessToken->created_at->gt(now()->subMinutes($this->expiration)))
            && (! $accessToken->expires_at || ! $accessToken->expires_at->isPast()) && !$accessToken->getAttribute('last_used_at');

        return $isValid;
    }
}