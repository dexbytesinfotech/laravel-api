<?php

namespace App\Http\Controllers\Api\Provider;

use App\Events\InstantMailNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Stores\Store;
use App\Models\Stores\StoreAddress;
use App\Models\Stores\StoreMetaData;
use App\Models\Stores\StoreOwners;
use App\Models\Users\UserMetaData;
use App\Http\Requests\Customer\OtpRequest;
use App\Http\Requests\Provider\SignUpManger;
use App\Http\Requests\Provider\SingupStoreRequest;
use App\Http\Resources\Provider\SinupStoreResource;
use App\Http\Requests\Provider\SingupProviderRequest;
use App\Models\User;
use App\Models\Util;

class RegistrationController extends BaseController
{
    public $storeAddressModel;
    public $storeMetaModel;
    public $userMetaModel;

    public function __construct()
    {
        $this->storeAddressModel = new StoreAddress;
        $this->storeMetaModel = new StoreMetaData();
        $this->userMetaModel = new UserMetaData();
    }
       /**
         * @OA\Post(
         * path="/api/provider/signup",
         * operationId="authProviderSingup",
         * tags={"Provider"},
         * summary="Provider signup",
         * description="Signup Provider",
         *     @OA\RequestBody(
         *         @OA\JsonContent(),
         *         @OA\MediaType(
         *            mediaType="multipart/form-data",
         *            @OA\Schema(
         *               type="object",
         *               required={"name", "provider_name", "provider_country_code", "provider_phone", "provider_email", "user_name", "date_of_birth", "nationality", "restaurent_address", "photo_id_proof", "profile_photo", "store_type", "number_of_branch","phone","country_code","commercial_records_certificate","address_line_1","landmark","latitude","longitude","country","city"},
         *               @OA\Property(property="name", type="text",description="Name"),
         *               @OA\Property(property="store_type", type="text",description="store_type(<small>Valid Only(Veg/nonveg)</small>)"),
         *               @OA\Property(property="number_of_branch", type="integer", description="number_of_branch"),
         *                @OA\Property(property="phone", type="integer", description="Phone"),
         *                @OA\Property(property="country_code", type="text", description="Country-Code"),
         *                @OA\Property(property="commercial_records_certificate", type="text", description="commercial-records-certificate"),
         *                @OA\Property(property="address_line_1", type="text", description="address-line-1"),
         *                @OA\Property(property="landmark", type="text", description="landmark"),
         *                @OA\Property(property="latitude", type="text", description="latitude"),
         *                @OA\Property(property="longitude", type="text", description="longitude"),
         *                @OA\Property(property="country", type="text", description="country"),
         *                @OA\Property(property="city", type="text", description="city"),
         *                @OA\Property(property="provider_name", type="text",description="Name"),
         *                @OA\Property(property="provider_country_code", type="text", description="Country-Code"),
         *                @OA\Property(property="provider_phone", type="text",description="phone"),
         *                @OA\Property(property="provider_email", type="text", description="email"),
         *                @OA\Property(property="user_name", type="text", description="User-Name"),
         *                @OA\Property(property="date_of_birth", type="date", description="date-of-birth"),
         *                @OA\Property(property="nationality", type="text", description="nationality"),
         *                @OA\Property(property="restaurent_address", type="text", description="restaurent-address"),
         *                @OA\Property(property="photo_id_proof", type="text", description="photo-id-proof"),
         *                @OA\Property(property="profile_photo", type="text", description="profile-photo")
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
    public function signupProvider(SingupProviderRequest $request, Store $store)
        {
            try
            {
            $validated = $request->validated();

            $user = User::where('phone',  $validated['provider_phone'])->first();
            if($user && $user->hasAnyRole(['Provider'])){
                return $this->sendError(trans('provider.There is an active account for the same mobile number'));
            }

            $request->merge(['application_status' => 'waiting']);
            $resource = $store->create($request->all());

            $request->merge(['store_id' => $resource->id]);
            $address = $this->storeAddressModel->create($request->all());

            $storeMeta = [
                [
                    'store_id'  => $resource->id,
                    'key'       => 'commercial_records_certificate',
                    'value'     => $request->commercial_records_certificate
                ],
                // [
                //     'store_id'  => $resource->id,
                //     'key'       => 'business_hours',
                //     'value'     =>  $store->getDefaultBusinessHours()
                // ]
            ];

            $defaultBusinessHours =  collect(json_decode($store->getDefaultBusinessHours(),true))->map(function ($value,$key) use($resource)
            {
                $value['store_id'] = $resource->id;
                $value['created_at'] = \Carbon\Carbon::now();
                return $value;
            })->all();
            \App\Models\Stores\BusinessHour::insert($defaultBusinessHours);

            $this->storeMetaModel->insert($storeMeta);
            $storeId = $resource->id;

            $userResponce = $this->_providerSingup($storeId, $validated);

            if($userResponce){
               return $this->sendResponse($userResponce, '');
            }
            $this->AccountRequestSend($user);

            return $this->sendResponse(['store_id' => $resource->id, 'user_id' =>  $user->id], "Store Created Successfully and something wrong with provider account!");

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    protected function _providerSingup($storeId, $validated){

                $mobileNumber = $validated['provider_phone'];
                $user = User::where('phone', $mobileNumber)->first();

                if(!$user) {
                    $verificationCode =  Util::generateOTP();
                    $user = User::create([
                        'name' => $validated['provider_name'],
                        'user_name' => $validated['user_name'],
                        'phone' => $mobileNumber,
                        'country_code' => $validated['provider_country_code'],
                        'password' => $mobileNumber,
                        'email' => $validated['provider_email'],
                        'remember_token' => Str::random(60),
                        'verification_code' => $verificationCode,
                        'profile_photo' => $validated['profile_photo'],
                        'global_notifications' => 1
                    ]);
                    $user->assignRole('Unverified', 'Provider');
                    //SMS API
                    $message = __("sms/provider.Dear :name,your sign up otp is :otp",['name' => $user->name,"otp" => $verificationCode]);
                    Util::sendMessage($mobileNumber, $message);

                } else {
                    if($user->hasAnyRole(['Provider'])){
                        return $this->sendError(trans('provider.Mobile number is already in use, please select another mobile number'));
                    }else{
                        $user->assignRole('Provider');
                        $verificationCode = Util::generateOTP();
                         User::where('id','=', $user->id)->update([
                            'verification_code' => $verificationCode,
                            'profile_photo' => $validated['profile_photo'],
                            'user_name' => $validated['user_name'],
                            'email' => $validated['provider_email'],
                        ]);

                        //SMS API
                        $message = __("sms/provider.Dear :name,your sign up otp is :otp",['name' => $user->name,"otp" => $verificationCode]);
                        Util::sendMessage($mobileNumber, $message);
                    }

                }

                // Set Auth Details
                $success = [];
                $success['token'] = $user->createToken('MyApp', ['provider'])->accessToken;

                $mil = $validated['date_of_birth'];
                $seconds = $mil / 1000;

                $meataDataArray = [
                   [
                        'user_id'  =>  $user->id,
                        'key'       => 'date_of_birth',
                        'value'     => date( "d/m/Y", $seconds)
                    ],
                    [
                        'user_id'  =>  $user->id,
                        'key'       => 'nationality',
                        'value'     => $validated['nationality']
                    ],
                    [
                        'user_id'  =>  $user->id,
                        'key'       => 'restaurent_address',
                        'value'     => $validated['restaurent_address']
                    ],
                    [
                        'user_id'  =>  $user->id,
                        'key'       => 'photo_id_proof',
                        'value'     =>  $validated['photo_id_proof']
                    ],
                    [
                        'user_id'  => $user->id,
                        'key'       => 'profile_photo' ,
                        'value'     => $validated['profile_photo']
                    ],
                ];


                $this->userMetaModel->insert($meataDataArray);

                StoreOwners::Create(
                    [
                        'store_id'  => $storeId,
                        'user_id'  => $user->id,
                    ]
                );

            return $success;
    }

// send request mail to admin
    public function AccountRequestSend($user)
        {
            $admin_id = User::whereHas(
                'roles', function($q){
                    $q->whereIn('guard_name', ['web','api']);
                    $q->where('name','Admin');
                }
            )->first()->id;
            event(new InstantMailNotification($admin_id, [
                "code" =>  'new_account_request_update',
                "args" => [
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                ]
            ]));
        }
         /**
        * @OA\Post(
        * path="/api/provider/signup-verify-otp",
        * operationId="otpSignupProviderMobileLogin",
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
                        $request->user()->token()->revoke();
                        return $this->sendResponse(['phone' => $user->phone], trans('provider.Your request sent sucessfully to support team and you will recieve a conformation message on your mobile number once your request approved'));
                    }

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage());
            }

        }

       /**
        * @OA\Post(
        * path="/api/provider/signup-store",
        * operationId="authSingupStore",
        * tags={"Provider"},
        * summary="Provider signup ",
        * description="Signup Provider ",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name", "store_type", "number_of_branch","phone","country_code","commercial_records_certificate","address_line_1","landmark","latitude","longitude","country","city"},
        *               @OA\Property(property="name", type="text",description="Name"),
        *               @OA\Property(property="store_type", type="text",description="store_type(<small>Valid Only(Veg/nonveg)</small>)"),
        *               @OA\Property(property="number_of_branch", type="integer", description="number_of_branch"),
        *                @OA\Property(property="phone", type="integer", description="Phone"),
        *                @OA\Property(property="country_code", type="text", description="Country-Code"),
        *                @OA\Property(property="commercial_records_certificate", type="text", description="commercial-records-certificate"),
        *                @OA\Property(property="address_line_1", type="text", description="address-line-1"),
        *                @OA\Property(property="landmark", type="text", description="landmark"),
        *                @OA\Property(property="latitude", type="text", description="latitude"),
        *                @OA\Property(property="longitude", type="text", description="longitude"),
        *                @OA\Property(property="country", type="text", description="country"),
        *                @OA\Property(property="city", type="text", description="city"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Store Singup successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Store Singup successfully",
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
        public function signupStore(SingupStoreRequest $request,Store $store)
        {
            try
            {
            $request->merge(['application_status' => 'waiting']);
            $resource = $store->create($request->all());
            $request->merge(['store_id'=> $resource->id]);

            $address = $this->storeAddressModel->create($request->all());

            $storeMeta = [
                [
                    'store_id'  => $resource->id,
                    'key'       => 'commercial_records_certificate',
                    'value'     => $request->commercial_records_certificate
                ],
                [
                    'store_id'  => $resource->id,
                    'key'       => 'business_hours',
                    'value'     =>  $store->getDefaultBusinessHours()
                ]

                ];

             $this->storeMetaModel->insert($storeMeta);

            return $this->sendResponse(['store_id' => $resource->id], trans('provider.Store Created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
        }


        /**
        * @OA\Post(
        * path="/api/provider/signup-manager",
        * operationId="authMangerSingup",
        * tags={"Provider"},
        * summary="Manger signup ",
        * description="Manger Signup Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={},
        *                @OA\Property(property="name", type="text",description="Name"),
        *                @OA\Property(property="country_code", type="text", description="Country-Code"),
        *                @OA\Property(property="phone", type="text",description="phone"),
        *                @OA\Property(property="email", type="text", description="email"),
        *                @OA\Property(property="user_name", type="text", description="User-Name"),
        *                @OA\Property(property="date_of_birth", type="date", description="date-of-birth"),
        *                @OA\Property(property="nationality", type="text", description="nationality"),
        *                @OA\Property(property="restaurent_address", type="text", description="restaurent-address"),
        *                @OA\Property(property="photo_id_proof", type="text", description="photo-id-proof"),
        *                @OA\Property(property="profile_photo", type="text", description="profile-photo"),
        *                @OA\Property(property="store_id", type="integer", description="store-id"),
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
        public function signupManager(SignUpManger $request)
        {
            try
            {
                $validated = $request->validated();
                $mobileNumber = $validated['phone'];
                $storeId = (integer) $request->get('store_id');

                $user = User::where('phone', $mobileNumber)->first();

                if(!$user) {
                    $verificationCode = Util::generateOTP();
                    $user = User::create([
                        'name' => $validated['name'],
                        'user_name' => $validated['user_name'],
                        'phone' => $mobileNumber,
                        'password' => $mobileNumber,
                        'email' => $validated['email'],
                        'remember_token' => Str::random(60),
                        'verification_code' => $verificationCode
                    ]);
                    $user->assignRole('Unverified');
                    //SMS API
                    $message = __("sms/provider.Dear :name,your sign up otp is :otp",['name' => $user->name,"otp" => $verificationCode]);
                    Util::sendMessage($mobileNumber, $message);

                } else {

                    if($user->hasAnyRole(['Provider'])){

                        return $this->sendError(trans('provider.Mobile number is already in use, please select another mobile number'));

                    }else{

                        $user->assignRole('Provider');
                        $verificationCode = Util::generateOTP();
                        $user = User::where('id','=', $user->id)->update(['verification_code' => $verificationCode]);
                        //SMS API
                        $message = __("sms/provider.Dear :name,your sign up otp is :otp",['name' => $user->name,"otp" => $verificationCode]);
                        Util::sendMessage($mobileNumber, $message);
                    }

                }

                // Set Auth Details
                $success['token'] = $user->createToken('MyApp', ['provider'])->accessToken;
                $mil = $request->date_of_birth;
                $seconds = $mil / 1000;

                $meataDataArray = [
                   [
                        'user_id'  =>  $user->id,
                        'key'       => 'date_of_birth',
                        'value'     => date( "d/m/Y", $seconds)
                    ],
                    [
                        'user_id'  =>  $user->id,
                        'key'       => 'nationality',
                        'value'     => $request->nationality
                    ],
                    [
                        'user_id'  =>  $user->id,
                        'key'       => 'restaurent_address',
                        'value'     => $request->restaurent_address
                    ],
                    [
                        'user_id'  =>  $user->id,
                        'key'       => 'photo_id_proof',
                        'value'     =>  $request->photo_id_proof
                    ],
                    [
                        'user_id'  => $user->id,
                        'key'       => 'profile_photo' ,
                        'value'     =>$request->profile_photo
                    ],
                ];


                $this->userMetaModel->insert($meataDataArray);

                StoreOwners::Create(
                    [
                        'store_id'  => $storeId,
                        'user_id'  => $user->id,
                    ]
                );

            return $this->sendResponse($success, trans('provider.Your request sent sucessfully to support team and you will recieve a conformation message on your mobile number once your request approved'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }

}
