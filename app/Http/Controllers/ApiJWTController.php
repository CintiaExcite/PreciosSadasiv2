<?php
//https://tutsforweb.com/restful-api-in-laravel-56-using-jwt-authentication/#disqus_thread
//https://appdividend.com/2018/06/22/nuxt-js-laravel-authentication-tutorial/
//https://blog.pusher.com/laravel-jwt/
namespace App\Http\Controllers;
 
use JWTAuth;
use App\User;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
 
class ApiJWTController extends Controller
{
    public $loginAfterSignUp = true;
 
    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
 
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
 
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Email y/o password inválidos',
            ], 401);
        }
        
        /*$this->validate($request, [
            'token' => 'required'
        ]); */
        //$user_auth = JWTAuth::authenticate($jwt_token);
        //$user_auth = JWTAuth::parseToken()->authenticate();
        //Log::logUser($user_auth->id, 100, $user_auth->id, $user_auth->name);
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
 
    public function logout(Request $request)
    {
        try {
            $user_auth = JWTAuth::parseToken()->authenticate();
            Log::logUser($user_auth->id, 101, $user_auth->id, $user_auth->name);

            JWTAuth::invalidate();
 
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
 
    public function getAuthUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['user' => $user]);
    }
}