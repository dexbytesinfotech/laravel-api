<?php

namespace App\Http\Middleware;

use App\Models\Push\PushDevice;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /*
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next ...$guards
    * @return mixed
    */
    public function handle($request, Closure $next, ...$guards)
    {
       if ($this->auth->user())
       {
           if ($this->auth->user()->status != true && $this->auth->user()->phone_verified_at != null)
           {
               PushDevice::where('user_id', $this->auth->user()->id)->update(['status' => 'inactive']);
               $this->auth->user()->token()->revoke();
               return response('Unauthorized.', 401);
           }
       }
       return $next($request);
   }
}
