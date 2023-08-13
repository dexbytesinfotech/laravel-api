<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Customer\LoginEmailRequest;
use App\Http\Resources\Users\LoginResource;
use App\Models\User;
use Auth;

class LoginController extends BaseController
{
    /**
        * @OA\Post(
        * path="/api/admin/login",
        * operationId="authAdminEamilLogin",
        * tags={"Admin"},
        * summary="Admin Login by email and password",
        * description="Admin login using email and password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email",description="Email (<small>char limit( 255)</small>)"),
        *               @OA\Property(property="password", type="password",description="Password (<small>char limit( 255)</small>)")
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
        public function login(LoginEmailRequest $request)
        {
            $validated = $request->validated();

            if(auth()->guard('web')->attempt( $validated )){
                config(['auth.guards.api.provider' => 'user']);

                $user = User::find(auth()->guard('web')->user()->id);
                if(!$user->hasRole('Admin')) {
                    return $this->sendError(trans('admin.Please login with admin privileges and try again'), ['error'=>'Unauthorised']);
                }
                $user['token'] =  $user->createToken('MyApp',['admin'])->accessToken;

                return $this->sendResponse(new LoginResource($user), trans('admin.You are successfully logged in'));

            }else{
                return $this->sendError(trans('admin.You have entered an invalid username or password'), ['error'=>'Unauthorised']);
            }
        }


         /**
        * @OA\Get(
        * path="/api/admin/logout",
        * operationId="adminLogout",
        * tags={"Admin"},
        * security={{"passport":{}}},
        * summary="Admin logout",
        * security={
        *   {"passport": {}}
        * },
        * description="Admin logout",
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
        public function logout(Request $request)
        {
            try {

                $request->user()->token()->revoke();
                return $this->sendResponse([], trans('admin.Logout successfully'));

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }

        }


}

