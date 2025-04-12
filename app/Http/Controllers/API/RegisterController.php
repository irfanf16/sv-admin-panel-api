<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Services\EncryptionDecryption;
use App\Http\Requests\UserRegistrationFormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Validator;
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'device' => 'required'
        ]);
        if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] =  $user->createToken($request->device)->plainTextToken;
        $success['name'] =  $user->first_name . ' ' . $user->last_name;
        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(UserRegistrationFormRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        event(new Registered($user));

        // $user = User::create($input);
        // $success['token'] =  $user->createToken($request->device)->plainTextToken;
        // $success['name'] =  $user->first_name . ' ' . $user->last_name;
        // return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, EncryptionDecryption $encryptionDecryption)
    {
//        dd( $request->all());
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required',
            'device'    => 'required',
            'ip'        => 'required|ip',
            'system_info' => 'required|array',
            'device_info' => 'required|array'
        ]);
        if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());
        }


        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();


            if (!$user->activated) {
                return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
            }

            $device = $request->device?? 'web';
            $success['superuser'] = $user->isSuperUser();
            $success['name'] =  $user->first_name . ' '. $user->last_name;
            $success['image'] =  $user->image;
            $success['expiration'] =  config('sanctum.expiration');
            if($user->isSuperUser()) {
                $success['token'] =  $user->createToken($device)->plainTextToken;
                $success['permissions'] = ['*'];
            } else {
                $permissions = $user->getAllPermissions()->pluck(['name'])->toArray();
                $success['token'] =  $user->createToken($device, $permissions)->plainTextToken;
                $success['permissions'] = $permissions;
            }

            $token = PersonalAccessToken::findToken($success['token']);
            $token->ip = $request->ip;
            $token->system_info = json_encode($request->system_info);
            $token->device_info = json_encode($request->device_info);
            $token->save();

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized'],401);
        }

    }

    public function logout(Request $request) {
      $request->user()->currentAccessToken()->delete();
      return $this->sendResponse([], 'User logout successfully.');
    }

    public function refresh(Request $request) {

        $validator = Validator::make($request->all(), [
            'device' => 'required'
        ]);
        if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());
        }

        $token = $request->header('Authorization');
        if (empty($token)) {
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        }

        $token1 = explode('Bearer ', $token);
        $token = PersonalAccessToken::findToken($token1[1]);
        if(empty($token1[1]) || empty($token)) {
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        }

        if (!$token->tokenable instanceof User) {
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        }

        $device = $request->device?? 'web';
        $success['superuser'] = $token->tokenable->isSuperUser();
        $success['expiration'] =  config('sanctum.expiration');
        if($token->tokenable->isSuperUser()) {
            $success['token'] =  $token->tokenable->createToken($device)->plainTextToken;
            $success['permissions'] = ['*'];
        } else {
            $permissions = $token->tokenable->getAllPermissions()->pluck(['name'])->toArray();
            $success['token'] =  $token->tokenable->createToken($device, $permissions)->plainTextToken;
            $success['permissions'] = $permissions;
        }
        // Once refresh token has issued. Remove old token.
        $token->tokenable->tokens()->where('id', $token->id)->delete();
        return $this->sendResponse($success, 'Token Refreshed Successfully.');

    }

}
