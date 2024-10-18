<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Http;

class OpenKabAuthGuard implements Guard
{
    protected $request;
    protected $user;
    protected $baseUrl;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->baseUrl = config('services.openkab.base_url');
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        if ($this->user) {
            return $this->user; // Return the cached user
        }

        // Get the bearer token from the request
        $token = $this->request->bearerToken();

        if ($token) {
            // Validate token with Laravel A
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])->get("{$this->baseUrl}/api/v1/validate-token");
            
            if ($response->successful()) {
                // Set the user to the response (which should be a user object/array)
                $this->user = $response->json('user'); // Cast response to object if necessary
                $this->user->abilities = $response->json('abilities');
            }
        }

        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function hasUser()
    {
        return ! is_null($this->user());
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(Authenticatable $user)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * {@inheritdoc}
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->user()?->id;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(array $credentials = [])
    {
        return ! is_null($this->user());
    }
}
