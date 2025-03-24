<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $generatedToken = $request->get('generate');
        // hanya satu token yang valid untuk user yang sama
        if ($generatedToken) {
            $request->user()->tokens->each(function ($token, $key) {
                $token->delete();
            });

            $token = $request->user()->createToken('auth_token')->plainTextToken;
            $lastToken = $request->user()->tokens()->first();
            $lastToken->plaintext = $token;
            $lastToken->save();
            return redirect()->route('token');
        }
        $token = $request->user()->tokens->first()?->plaintext ?? null;
        return view('token.index', compact('token'));
    }
}
