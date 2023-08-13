<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Customer\ProfileResource;
use App\Http\Requests\Customer\ProfileUpdateRequest;
use App\Http\Requests\Customer\PhoneUpdateRequest;
use App\Http\Resources\Tickets\TicketResource;
use App\Events\InstantMailNotification;
use App\Models\Tickets\TicketCategory;
use App\Models\User;
use App\Models\Tickets\Ticket;

class ProfileController extends BaseController
{


    /**
        * @OA\Get(
        * path="/api/customer/profile",
        * operationId="authcustomerProfile",
        * tags={"Customer"},
        * summary="Get customer profile",
        * security={{"passport":{}}},
        * description="Get customer profile",
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
                return $this->sendResponse(new ProfileResource($user), 'Successfully.');

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }



    /**
     * @OA\Post(
     * path="/api/customer/profile-update",
     * operationId="customerProfileupdate",
     * tags={"Customer"},
     * security={{"passport":{}}},
     * summary="Customer profile update",
     * description="Customer profile update",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="name", type="string"),
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

            User::where('id', '=', auth()->user()->id)->update($request->all());
            $user = User::find(auth()->user()->id);

            return $this->sendResponse(new ProfileResource($user), trans('customer.Profile updated Successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/customer/mobile-number-update",
     * operationId="customerProfileUpdatePhone",
     * tags={"Customer"},
     * security={{"passport":{}}},
     * summary="Customer update mobile number",
     * security={
     *   {"passport": {}}
     * },
     * description="Customer update mobile number",
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

            if ($orderExists) {
                return $this->sendError(trans('customer.You already have pending change mobile number order'));
            }

            $payload = [];
            $payload['title'] = trans('customer.Change Mobile Number');
            $payload['user_id'] = auth()->user()->id;
            $payload['content'] = json_encode($validated);
            $payload['category_id'] = $categoryId;

            $resource = $ticket->create($payload);

            $this->MobileNumberUpdateRequest();
            return $this->sendResponse(new TicketResource($resource), trans('customer.Change mobile number order sent successfully to support team, the support team will be contact you to complete changing mobile number, Thank you'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
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
                'mobile' => $user->phone,
            ]
        ]));
    }


    /**
     * @OA\Get(
     * path="/api/customer/delete",
     * operationId="DeletecustomerAccount",
     * tags={"Customer"},
     * summary="Delete customer account",
     * security={{"passport":{}}},
     * description="Delete customer account",
     *      description="Deletes a record and returns no content",
     *       @OA\Response(
     *          response=200,
     *          description="Account deleted successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function delete(Request $request)
    {
       
        User::find(auth()->user()->id)->delete();
        $request->user()->token()->revoke();
        
        return $this->sendResponse([], trans('customer.Account deleted successfully'));
        
    }


}
