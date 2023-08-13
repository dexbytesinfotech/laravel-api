<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Customer\LoginEmailRequest;
use App\Http\Requests\Customer\LoginMobileRequest;
use App\Http\Requests\Customer\OtpRequest;
use App\Http\Resources\Customer\LoginCollection;
use App\Http\Requests\Customer\SingupRequest;
use App\Models\Push\PushDevice;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Util;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Constants\Roles;

class LoginController extends BaseController
{
    use ThrottlesLogins;

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;


    public function username()
    {
        return 'mobile';
    }
    
    /**
     * @OA\Post(
     * path="/api/customer/login-by-mobile",
     * operationId="authMobileLogin",
     * tags={"Customer"},
     * summary="Customer Login by mobile",
     * description="Login Customer Here using mobile",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"mobile", "country_code"},
     *               @OA\Property(property="mobile", type="text",description="Mobile"),
     *               @OA\Property(property="country_code", type="text", description="Country Code")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="OTP sent successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="OTP sent successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function loginMobile(LoginMobileRequest $request)
    {
        try {
 
            if(method_exists($this, 'hasTooManyLoginAttempts') &&  $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            $validated = $request->validated();
            
            $mobileNumber = $validated['country_code'] . $validated['mobile'];
            $user = User::where('phone', $mobileNumber)->first();

            if (method_exists($this, 'incrementLoginAttempts')){
                $this->incrementLoginAttempts($request);
            }

            if (!$user) {  
                return $this->sendError(trans('customer.Please enter the login information correctly'));
            }
            if(!$user->status) {
                return $this->sendError(trans('customer.Your account has been deactivated, Please contact to administrator'), ['error'=>'Unauthorised']);
            }
            // Set Auth Details
            $success['token']   = $user->createToken('MyApp', ['verification'])->accessToken;
            $success['mobile']  = $mobileNumber;


            if($user->is_demo){
                $verificationCode = '1234';
            }else{
                $verificationCode = Util::generateOTP();

                $message = __("sms/customer.Dear :name,your sign in otp is :otp",['name' => $user->first_name,"otp" => $verificationCode]);
                Util::sendMessage($mobileNumber, $message);
            }
            User::where('id', '=', $user->id)->update(['verification_code' => $verificationCode]);

            return $this->sendResponse($success, trans('customer.OTP sent successfully'));
      
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }


    /**
     * @OA\Post(
     * path="/api/customer/signup",
     * operationId="authMobileSingup",
     * tags={"Customer"},
     * summary="Customer signup by mobile and name",
     * description="Login Customer Here using mobile and name",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"mobile", "country_code", "first_name", "last_name"},
     *               @OA\Property(property="first_name", type="text",description="First name"),
     *               @OA\Property(property="last_name", type="text",description="Last name"),
     *               @OA\Property(property="mobile", type="text",description="Mobile"),
     *               @OA\Property(property="country_code", type="text", description="Country Code")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="OTP sent successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="OTP sent successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function signup(SingupRequest $request)
    {
        try {
            $validated = $request->validated();
            $mobileNumber = $validated['country_code'] . $validated['mobile'];
            $user = User::where('phone', $mobileNumber)->first();

            if (!$user) {

                $user = User::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $mobileNumber,
                    'password' => $mobileNumber,
                    'country_code' => $validated['country_code'],
                    'remember_token' => Str::random(60),
                    'global_notifications' => 1
                ]);

                $user->assignRole(Roles::UNVERIFIED, Roles::CUSOTMER);

                // Set Auth Details
                $success['token'] = $user->createToken('MyApp', ['verification'])->accessToken;
                $success['mobile'] = $mobileNumber;
                $success['first_name'] = $validated['first_name'];

                $verificationCode =  Util::generateOTP();
                User::where('id', '=', $user->id)->update(['verification_code' => $verificationCode]);

                $message = __("sms/customer.Dear :name,your sign up otp is :otp",['name' => $user->first_name.' '. $user->last_name,"otp" => $verificationCode]);
                Util::sendMessage($mobileNumber, $message);


                return $this->sendResponse($success, trans('customer.OTP sent successfully'));
            } else {

                if ($user->hasAnyRole([Roles::CUSOTMER, Roles::UNVERIFIED])) {
                    return $this->sendError(trans('customer.This mobile number already exists please verify and try to login'));
                } else {

                    $user->assignRole(Roles::CUSOTMER);

                    // Set Auth Details
                    $success['token'] = $user->createToken('MyApp', [Roles::CUSOTMER])->accessToken;
                    $success['mobile'] = $mobileNumber;
                    $success['first_name'] = $validated['first_name'];

                    $verificationCode =  Util::generateOTP();
                    User::where('id', '=', $user->id)->update(['verification_code' => $verificationCode]);

                    //SMS API
                    $message = __("sms/customer.Dear :name,your sign up otp is :otp",['name' => $user->first_name .' '. $user->last_name, "otp" => $verificationCode]);
                    Util::sendMessage($mobileNumber, $message);

                    return $this->sendResponse($success, trans('customer.OTP sent successfully.'));
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }

 

    /**
     * @OA\GET(
     * path="/api/customer/logout/deviceId",
     * operationId="customerLogout",
     * tags={"Customer"},
     * security={{"passport":{}}},
     * summary="Customer logout",
     * security={
     *   {"passport": {}}
     * },
     * description="Customer logout",
     *      @OA\Response(
     *          response=201,
     *          description="Logout successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Logout successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function logout(Request $request,$deviceId = null)
        {
            try {
                if ($deviceId) {
                    PushDevice::where('user_id','=', auth()->user()->id)->where('device_uid', $request->deviceId)->update(['status' => 'inactive']);
                }
                $request->user()->token()->revoke();
                return $this->sendResponse([], trans('customer.Logout successfully'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }

}
