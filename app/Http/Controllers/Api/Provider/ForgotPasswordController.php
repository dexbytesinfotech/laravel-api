<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\LoginMobileRequest;
use App\Http\Requests\Customer\OtpRequest;
use App\Http\Requests\Provider\CreatePasswordRequest;
use App\Http\Resources\Customer\LoginCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Events\InstantMailNotification;
use App\Models\Util;

class ForgotPasswordController extends BaseController
{
     /**
        * @OA\Post(
        * path="/api/provider/forgot-password",
        * operationId="authProviderMobileForgotPassword",
        * tags={"Provider"},
        * summary="Provider forgot password by mobile and verify otp",
        * description="Provider forgot password by mobile and verify otp",
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
        public function forgotPasswordByMobile(LoginMobileRequest $request)
        {
            try
            {
                $validated = $request->validated();
                $mobileNumber = $validated['country_code'].$validated['mobile'];
                $user = User::where('phone', $mobileNumber)->first();

                if(!$user) {
                    return $this->sendError(trans('provider.Please login with Provider privileges and try again'));
                }

                // Set Auth Details
                $success['token'] = $user->createToken('MyApp', ['provider'])->accessToken;
                $success['mobile'] = $mobileNumber;

                $verificationCode = Util::generateOTP();
                User::where('id','=', $user->id)->update(['verification_code' => $verificationCode]);

                //SMS API
                $message = __("sms/provider.Dear :name,your forget password otp is :otp",['name' => $user->name,"otp" => $verificationCode]);
                Util::sendMessage($mobileNumber, $message);

                $this->ForgetPasswordMail($verificationCode);
                return $this->sendResponse($success, trans('provider.OTP sent successfully'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }

        public function ForgetPasswordMail($otp)
        {
            $user = auth()->user();
            event(new InstantMailNotification($user->id, [
                "code" =>  'forget_password',
                "args" => [
                    'name' => $user->name,
                    'mobile' => $user->phone,
                    'otp'   => $otp
                ]
            ]));
        }

      /**
        * @OA\Post(
        * path="/api/provider/verify-otp",
        * operationId="otpProviderMobileLogin",
        * tags={"Provider"},
        * security={{"passport":{}}},
        * summary="Provider OTP Verify",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider OTP Verify",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"otp"},
        *               @OA\Property(property="otp", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="OTP verify successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="OTP verify successfully",
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
                    $validated = $request->validated();
                    $verificationCode = $validated['otp'];

                    $user  = User::where([['id','=', auth()->user()->id],['verification_code','=', $verificationCode]])->first();

                    if(!$user) {
                        return $this->sendError(trans('provider.Invalid OTP'), ['error'=>'Unauthorised']);

                    } else {

                        if($user->hasRole('Unverified')){
                            $user->removeRole('Unverified');
                        }

                        if(!$user->hasRole('Provider')){
                            $user->assignRole('Provider');
                        }

                        User::where('id','=', auth()->user()->id)->update(['verification_code' => '', 'phone_verified_at' => \Carbon\Carbon::now()]);

                        return $this->sendResponse(['name' => $user->name,'phone' => $user->phone], trans('provider.OTP verify Successfully'));
                    }

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }

         /**
        * @OA\Post(
        * path="/api/provider/create-password",
        * operationId="authProviderCreatePassword",
        * tags={"Provider"},
        * summary="Provider Create password",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider Create password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"new_password", "confirm_password"},
        *               @OA\Property(property="new_password", type="text", description="New Password"),
        *               @OA\Property(property="confirm_password", type="text", description="Confirm Password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Change passowrd Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Change passowrd Successfully",
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
        public function createPassword(CreatePasswordRequest $request)
        {
            $validated = $request->validated();

            try {

                if ((Hash::check($validated['new_password'], auth()->user()->password)) == true) {

                    return $this->sendError(trans('provider.Please enter a password which is not similar then current password'));

                } else {

                    User::where('id', auth()->user()->id)->update(['password' => Hash::make($validated['new_password'])]);
                    $request->user()->token()->revoke();

                    return $this->sendResponse([], trans('provider.Password updated successfully'));

                }

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }


}
