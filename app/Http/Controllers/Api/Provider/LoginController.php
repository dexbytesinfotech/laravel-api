<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Provider\LoginPhoneRequest;
use App\Http\Resources\Users\ProviderLoginResource;
use App\Models\Push\PushDevice;
use App\Models\User;
use App\Models\Stores\Store;
use Auth;
use App\Models\Util;


class LoginController extends BaseController
{

    /**
        * @OA\Post(
        * path="/api/provider/login",
        * operationId="authpPoviderLogin",
        * tags={"Provider"},
        * summary="Provider Login by phone and password",
        * description="Provider login using phone and password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"country_code", "phone", "password"},
        *               @OA\Property(property="country_code", type="text", description="Country Code"),
        *               @OA\Property(property="phone", type="text",description="Phone"),
        *               @OA\Property(property="password", type="password",description="Password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
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
        public function login(LoginPhoneRequest $request)
        {
            $validated = $request->validated();

            if(auth()->guard('web')->attempt(['phone' => $validated['phone'], 'password' => $validated['password']])){
                config(['auth.guards.api.provider' => 'user']);

                $user = User::find(auth()->guard('web')->user()->id);
                if(!$user->hasRole('Provider')) {
                    return $this->sendError(trans('provider.Please login with provider privileges and try again'), ['error'=>'Unauthorised']);
                }
                if(!$user->status) {
                    return $this->sendError(trans('provider.Your account has been deactivated, Please contact to administrator'), ['error'=>'Unauthorised']);
                }
                $user['token'] =  $user->createToken('MyApp',['provider'])->accessToken;

                if(!empty($request->fcmToken) ){

                    PushDevice::updateOrCreate(
                        ['device_uid' => $request->deviceId,
                        'app_name' => 'provider']
                        ,[
                        'user_id'=> $user->id,
                        'device_uid' => $request->deviceId,
                        'device_name' =>  $request->deviceName,
                        'device_token_id' =>$request->fcmToken,
                        'device_version' =>$request->deviceVersion,
                        'device_type' => $request->deviceType,
                        'app_name' => 'provider',
                        'app_version' => $request->app_version,
                        'device_model' => $request->device_model,
                        'status' => 'active',
                    ]);

                }

                if(empty($user->store->store_id) && !is_numeric($user->store->store_id)){
                    return $this->sendError(trans('provider.Your account not associated with a restaurant as a manager, Please contact to administrator'));
                }

                $applicationStatus = Store::whereNull('deleted_at')->where('id', $user->store->store_id )->pluck('application_status')->first();
                $user->application_status = $applicationStatus ;
                if(!empty($applicationStatus) && $applicationStatus == 'suspended'){
                    return $this->sendError(trans('provider.Your account has been suspended, Please contact to administrator'));
                }

                return $this->sendResponse(new ProviderLoginResource($user), trans('provider.You are successfully logged in'));

            }else{
                return $this->sendError(trans('provider.You have entered an invalid phone number or password'), ['error'=>'Unauthorised']);
            }
        }

     /**
     * @OA\GET(
     * path="/api/provider/resend-otp",
     * operationId="resendOtpProviderMobile",
     * tags={"Provider"},
     * security={{"passport":{}}},
     * summary="Provider resend OTP",
     * security={
     *   {"passport": {}}
     * },
     * description="Provider resend OTP",
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

            $verificationCode = Util::generateOTP();
            User::where('id', '=', auth()->user()->id)->update(['verification_code' =>  $verificationCode]);

            //SMS API
            $message = __("sms/provider.Dear :name,your resent otp is :otp",['name' => auth()->user()->name,"otp" => $verificationCode]);
            Util::sendMessage(auth()->user()->phone, $message);

            return $this->sendResponse(['phone' => auth()->user()->phone], trans('provider.OTP sent successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }


          /**
        * @OA\Get(
        * path="/api/provider/logout/deviceId",
        * operationId="providerLogout",
        * tags={"Provider"},
        * security={{"passport":{}}},
        * summary="Provider logout",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider logout",
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
                return $this->sendResponse([], trans('provider.Logout successfully'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }


}
