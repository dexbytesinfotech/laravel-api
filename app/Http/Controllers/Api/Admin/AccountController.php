<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Http\Resources\Users\UserResource;
use App\Http\Requests\Users\ChangePasswordRequest;
use Auth;
use Hash;

class AccountController extends BaseController
{
    /**
        * @OA\Get(
        * path="/api/admin/profile",
        * operationId="authAdminProfile",
        * tags={"Admin"},
        * summary="Get admin profile",
        * security={{"passport":{}}},
        * description="Get admin profile",
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
                return $this->sendResponse(new UserResource($user), 'Successfully.');

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }


        /**
        * @OA\Post(
        * path="/api/admin/change-password",
        * operationId="authAdminChangePassword",
        * tags={"Admin"},
        * summary="Admin change password",
        * security={
        *   {"passport": {}}
        * },
        * description="Admin change password",
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

                    return $this->sendError(trans('admin.Check your old password'));

                } else if ((Hash::check($validated['new_password'], auth()->user()->password)) == true) {

                    return $this->sendError(trans('admin.Please enter a password which is not similar then current password'));

                } else {

                    User::where('id', auth()->user()->id)->update(['password' => Hash::make($validated['new_password'])]);

                    return $this->sendResponse([], trans('admin.Password updated successfully'));

                }

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }


}

