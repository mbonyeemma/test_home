<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class OAuthController extends Controller
{
    public function redirect()
    {
    	$queries = http_build_query([
    		'client_id' => env('SSO_CLIENT_ID'),
    		'redirect_url' => 'http://10.200.254.44/oauth/callback',
    		'response_type' => 'code'
    		]);
    	return redirect('http://10.200.254.40/oauth/authorize?'. $queries);
    }

    public function callback(Request $request)
    {
    	$client = new \GuzzleHttp\Client();

        $response = $client->post('http://10.200.254.40/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => env('SSO_CLIENT_ID'),
            'client_secret' => env('SSO_SECRET'),
            'redirect_url' => env('SSO_CALLBACK_URL'),
            'code' => $request->code,
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        $response_userID = $response['user_id'];  
          // Return all user where idp_key is the same as the user returned in the response
        $user = User::where('idp_key', '=', $response_userID)->first();

        if($user == ''){
            return redirect('http://10.200.254.44/main')
                 ->with('message','Contact System Administrator');
        }else{
        Auth::login($user);

            return redirect('/dashboard');
        }

        // IF THE USER IN THE IDP SERVER IS AUTHENTICATED (HAS TRUE USERNAME AND PASSWORD)?

        // CHECK IF THAT USER HAS A FOREIGN KEY IN THE CLIENT APP AND THEN LOG IN.

        // $request->user()->token()->delete();
        // $request->user()->token()->create([
        //     'access_token' => $response['access_token'],
        //     'expires_in'   => $response['expires_in'],
        //     'refresh_token'   => $response['refresh_token']
        // ]);

    }

    public function refresh(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post('http://localhost/oauth/token', [
        'form_params' => [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->user()->token->refresh_token,
            'client_id' => '3',
            'client_secret' => 'Ft0octVfpclF2SR6KCkD3Ozv14g6J3BzCMMz7twq',
            'redirect_url' => 'http://test.site/oauth/callback',
            'code' => $request->code,
            ],
        ]);

        $response = json_decode($response->getBody(), true);

        $request->user()->token()->update([
            'access_token' => $response['access_token'],
            'expires_in'   => $response['expires_in'],
            'refresh_token'   => $response['refresh_token']
        ]);

        return redirect('/home');
    }

}
