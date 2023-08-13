<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PhoneUpdateRequest;
use App\Http\Requests\Customer\ProfileUpdateRequest;
use App\Http\Requests\Provider\emailOtpRequest;
use App\Http\Requests\Provider\isOpenStoreRequest;
use App\Http\Requests\Provider\updateEmailRequest;
use App\Http\Requests\Stores\UpdateStoreRequest;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Resources\Provider\isOpenResource;
use App\Http\Resources\Stores\StoreResource;
use App\Http\Resources\Tickets\TicketResource;
use App\Events\InstantMailNotification;
use App\Http\Resources\Users\ProviderResource;
use App\Models\Stores\Store;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\Util;

class AccountController extends BaseController
{
    /**
        * @OA\Get(
        * path="/api/provider/profile",
        * operationId="authproviderProfile",
        * tags={"Provider"},
        * summary="Get provider profile",
        * security={{"passport":{}}},
        * description="Get provider profile",
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
        public function profile()
        {
            try{
                $user = User::find(auth()->user()->id);

                if(empty($user->store->store_id) && !is_numeric($user->store->store_id)){
                    return $this->sendError(trans('provider.Your account not associated with a restaurant as a manager, Please contact to administrator'));
                }

                $applicationStatus = Store::whereNull('deleted_at')->where('id', $user->store->store_id )->pluck('application_status')->first();
                $user->application_status = $applicationStatus ;
                if(empty($applicationStatus) && $applicationStatus == 'suspended'){
                    return $this->sendError(trans('provider.Your account has been suspended, Please contact to administrator'));
                }

                return $this->sendResponse(new ProviderResource($user), 'Successfully.');

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }


        /**
        * @OA\Post(
        * path="/api/provider/change-password",
        * operationId="authproviderChangePassword",
        * tags={"Provider"},
        * summary="Provider change password",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider change password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"old_password", "new_password", "confirm_password"},
        *               @OA\Property(property="old_password", type="text", description="Old Password"),
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
        public function changePassword(ChangePasswordRequest $request)
        {
            $validated = $request->validated();

            try {
                if ((Hash::check($validated['old_password'], auth()->user()->password)) == false) {

                    return $this->sendError(trans('provider.Check your old password'));

                } else if ((Hash::check($validated['new_password'], auth()->user()->password)) == true) {

                    return $this->sendError(trans('provider.Please enter a password which is not similar then current password.'));

                } else {

                    User::where('id', auth()->user()->id)->update(['password' => Hash::make($validated['new_password'])]);

                    return $this->sendResponse([], trans('provider.Password updated successfully.'));

                }

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }


    /**
    * @OA\Post(
    * path="/api/provider/profile-update",
    * operationId="providerProfileupdate",
    * tags={"Provider"},
    * security={{"passport":{}}},
    * summary="Provider profile update",
    * security={
    *   {"passport": {}}
    * },
    * description="Provider profile update",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               @OA\Property(property="name", type="string"),
    *                @OA\Property(property="email", type="string"),
    *               @OA\Property(property="global_notifications", type="integer"),
    *               @OA\Property(property="default_lang", type="string")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Profile updated successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Profile updated successfully",
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
    public function update(ProfileUpdateRequest $request)
    {
        try {

            User::where('id','=', auth()->user()->id)->update($request->all());
            $user = User::find(auth()->user()->id);

            return $this->sendResponse(new ProviderResource($user), trans('provider.Profile updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }

    }

     /**
        * @OA\Post(
        * path="/api/provider/mobile-number-update",
        * operationId="ProviderProfileUpdatePhone",
        * tags={"Provider"},
        * security={{"passport":{}}},
        * summary="Provider update mobile number",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider update mobile number",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"mobile", "country_code"},
        *               @OA\Property(property="mobile", type="string"),
        *               @OA\Property(property="country_code", type="text", description="Country Code")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Request send successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Request send successfully",
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
        public function updatePhone(PhoneUpdateRequest $request, Ticket $ticket)
        {
            try {

                $validated = $request->validated();
                $categoryId = TicketCategory::where('name', 'update-mobile-number')->first()->id;

                 $orderExists = Ticket::where('category_id', $categoryId)->where('user_id', auth()->user()->id)->where('status', 'open')->exists();

                if($orderExists){
                    return $this->sendError(trans('provider.You already have pending change mobile number order'));
                }

                $payload = [];
                $payload['title'] = trans('provider.Change Mobile Number');
                $payload['user_id'] = auth()->user()->id;
                $payload['content'] = json_encode($validated);
                $payload['category_id'] = $categoryId;

                $resource = $ticket->create($payload);
                $this->MobileNumberUpdateRequest();

                return $this->sendResponse(new TicketResource($resource), trans('provider.Change mobile number order sent successfully to support team, the support team will be contact you to complete changing mobile number, Thank you'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }

        // send mobile number updation request to admin
        public function MobileNumberUpdateRequest()
        {
            $user = auth()->user();
            $admin_id = User::whereHas(
                'roles', function($q){
                    $q->whereIn('guard_name', ['web','api']);
                    $q->where('name','Admin');
                }
            )->first()->id;
            event(new InstantMailNotification($admin_id, [
                "code" =>  'mobile_number_update',
                "args" => [
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                ]
            ]));
        }

         /**
        * @OA\Post(
        * path="/api/provider/is-open",
        * operationId="authProviderIsOpen",
        * tags={"Provider"},
        * summary="Provider IsOpen",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider IsOpen",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"store_id", "is_open"},
        *               @OA\Property(property="store_id", type="integer", description="Store ID"),
        *               @OA\Property(property="is_open", type="string", description="Is-Open(<small></small>ValidOnly(0,1)</small>)"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="is Open Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="is open Successfully",
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

    public function isOpen(isOpenStoreRequest $request)
    {
        try
        {   $store = Store::FindOrFail($request->store_id);
            $store->update($request->all());
            return $this->sendResponse(new isOpenResource($store), trans('provider.Store updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

      /**
        * @OA\Post(
        * path="/api/provider/update-store",
        * operationId="authProviderStoreUpdate ",
        * tags={"Provider"},
        * summary="Provider Store Update ",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider Store Update ",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"store_id", "logo_path"},
        *               @OA\Property(property="store_id", type="integer", description="Store ID"),
        *               @OA\Property(property="logo_path", type="string", description="logo image file path)"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Store Update Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Store Update Successfully",
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

        public function updateStore(Request $request)
        {
            try
            {   $store = Store::FindOrFail($request->store_id);
                $store->update($request->all());

                return $this->sendResponse(new StoreResource($store), trans('provider.Store updated Successfully'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }


 /**
        * @OA\Post(
        * path="/api/provider/email-update",
        * operationId="authpPoviderEmailUpdate",
        * tags={"Provider"},
        * summary="Provider Email Update",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider Update Email",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email"},
        *               @OA\Property(property="email", type="text", description="Email"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="The verification code sent to your email address",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="The verification code sent to your email address",
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

        public function emailUpdate(updateEmailRequest $request)
        {
            try
            {
                $verificationCode =  Util::generateOTP();
                $user = User::where('id','=', auth()->user()->id)->update(['verification_code' => $verificationCode]);

                //Email API
                $message = 'Your OTP is : '. $verificationCode;

                $this->emailUpdateRequest($verificationCode);

                return $this->sendResponse(['email'=> $request->email], trans('provider.The verification code sent to your email address.'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage());
            }
        }

        public function emailUpdateRequest($verificationCode)
        {
            $user = auth()->user();
            event(new InstantMailNotification(auth()->user()->id, [
                "code" =>  'email_request_update',
                "args" => [
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                    'verification_code' => $verificationCode
                ]
            ]));
        }

        /**
        * @OA\Post(
        * path="/api/provider/verify-email-otp",
        * operationId="otpProviderUpdateEmail",
        * tags={"Provider"},
        * security={{"passport":{}}},
        * summary="Provider Email OTP Verify",
        * security={
        *   {"passport": {}}
        * },
        * description="Provider Email OTP Verify",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"otp","email"},
        *               @OA\Property(property="otp", type="text", description="OTP"),
        *                @OA\Property(property="email", type="text", description="Email"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Email address added successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Email address added successfully",
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
        public function verifyEmailOtp(emailOtpRequest $request)
        {
            try {
                    $validated = $request->validated();
                    $verificationCode = $validated['otp'];
                    $email = $validated['email'];

                    $user  = User::where([['id','=', auth()->user()->id], ['verification_code','=', $verificationCode]])->first();

                    if(!$user) {
                        return $this->sendError(trans('provider.Invalid Verification Code'));
                    } else {

                        User::where('id','=', auth()->user()->id)->update(['email' => $email, 'verification_code' => '', 'email_verified_at' => \Carbon\Carbon::now()]);

                        return $this->sendResponse(['email'=> $email] , trans('provider.Email Update Otp verfied succesfully'));
                    }

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }

}
