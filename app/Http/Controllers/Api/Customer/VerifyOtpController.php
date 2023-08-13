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

class VerifyOtpController extends BaseController
{
    use ThrottlesLogins;

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;


    public function username()
    {
        return auth()->user()->phone;
    }
 

    /**
     * @OA\Post(
     * path="/api/customer/verify-otp",
     * operationId="VerifyOtpMobileLogin",
     * tags={"Customer"},
     * security={{"passport":{}}},
     * summary="Customer OTP Verify",
     * security={
     *   {"passport": {}}
     * },
     * description="Customer OTP Verify",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"otp"},
     *               @OA\Property(property="otp", type="text"),
     *               @OA\Property(property="deviceId", type="integer"),
     *               @OA\Property(property="deviceName", type="text"),
     *               @OA\Property(property="fcmToken", type="integer"),
     *               @OA\Property(property="deviceVersion", type="integer"),
     *               @OA\Property(property="deviceType", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successfully",
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
    public function verifyOtp(OtpRequest $request)
    {
        try {
            
            $validated =  $request->validated();
          
            $verificationCode = $validated['otp'];
            $user  = User::where([['id', '=', auth()->user()->id], ['verification_code', '=', $verificationCode]])->first();

            if (!$user) {

                if(method_exists($this, 'hasTooManyLoginAttempts') &&  $this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);
                    $request->user()->token()->revoke(); //delete token
                    return $this->sendLockoutResponse($request);
                }
                
                if (method_exists($this, 'incrementLoginAttempts')){
                    $this->incrementLoginAttempts($request);
                }

                return $this->sendError(trans('customer.Invalid OTP'));
                
            } else {
                $request->user()->token()->revoke(); //delete old token
                
                if ($user->hasRole(Roles::UNVERIFIED)) {
                    $user->removeRole(Roles::UNVERIFIED);
                }

                if (!$user->hasRole(Roles::CUSOTMER)) {
                    $user->assignRole(Roles::CUSOTMER);
                }

                $user->token = $user->createToken('MyApp', [Roles::CUSOTMER])->accessToken;

                User::where('id', '=', auth()->user()->id)->update(['verification_code' => '', 'phone_verified_at' => \Carbon\Carbon::now()]);

                if(!empty($request->fcmToken) ){

                    PushDevice::updateOrCreate(
                        ['device_uid' => $request->deviceId,
                        'app_name' => Roles::CUSOTMER],
                        [
                        'user_id'=> auth()->user()->id,
                        'device_uid' => $request->deviceId,
                        'device_name' =>  $request->deviceName,
                        'device_token_id' =>$request->fcmToken,
                        'device_version' =>$request->deviceVersion,
                        'device_type' => $request->deviceType,
                        'app_name' => Roles::CUSOTMER,
                        'app_version' => $request->app_version,
                        'device_model' => $request->device_model,
                        'status' => 'active',
                    ]);
                }

                return $this->sendResponse(new LoginCollection($user), trans('customer.Login Successfully'));
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }



    /**
     * @OA\GET(
     * path="/api/customer/resend-otp",
     * operationId="ResendOtpMobileLogin",
     * tags={"Customer"},
     * security={{"passport":{}}},
     * summary="Customer resend OTP",
     * security={
     *   {"passport": {}}
     * },
     * description="Customer resend OTP",
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
    public function resendOtp(Request $request)
    {
        try {
            if(method_exists($this, 'hasTooManyLoginAttempts') &&  $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                $request->user()->token()->revoke(); //delete token
                return $this->sendLockoutResponse($request);
            }
            
            if (method_exists($this, 'incrementLoginAttempts')){
                $this->incrementLoginAttempts($request);
            }

            $verificationCode =  Util::generateOTP();
            User::where('id', '=', auth()->user()->id)->update(['verification_code' => $verificationCode]);

            //SMS API
            $message = __("sms/customer.Dear :name,your resent otp is :otp",['name' => auth()->user()->first_name,"otp" => $verificationCode]);
            Util::sendMessage(auth()->user()->phone, $message);

         return $this->sendResponse(['phone' => auth()->user()->phone], trans('customer.OTP sent successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }


 
}
