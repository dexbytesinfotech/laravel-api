<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Users\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/user",
        * operationId="User",
        * tags={"User"},
        * summary="Get list of User",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of User",
        *      @OA\Response(
        *          response=201,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully",
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
    public function index()
    {
        $users =  UserCollection::collection(User::with(['userMetaData', 'device', 'store'])->orderBy('id', 'desc')->paginate(config('app_settings.pagination_per_page.value')));
        $pagination = [
            "current_page" => (integer) $users->currentPage(),
            "prev_page_api_url" => (string) $users->previousPageUrl(),
            "next_page_api_url" => (string) $users->nextPageUrl(),
            "last_page" => (integer) $users->lastPage(),
            "per_page" => (integer) $users->perPage(),
            "total" => (integer) $users->total()
        ];

       return $this->sendResponse($users, 'Successfully.', $pagination);
    }

    /**
     * @OA\POST(
     * path="/api/user",
     * operationId="UserStore",
     * tags={"User"},
     * summary="Store User",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns User data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "email", "phone", "password", "role_id", "country_code"},
     *               @OA\Property(property="name", type="text",description="Name"),
     *               @OA\Property(property="email", type="text",description="Email"),
     *               @OA\Property(property="phone", type="integer", description="Phone"),
     *               @OA\Property(property="country_code", type="integer",description="Country code"),
     *               @OA\Property(property="password", type="password",description="Password"),
     *               @OA\Property(property="role_id", type="integer",description="Role ID")
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="User created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User created Successfully",
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
    public function store(StoreUserRequest $request, User $user)
    {
        try
        {
            $role_id = $request->input('role_id');
            $payload = $request->all();

            unset($payload['role_id']);

            $resource = $user->create($payload);

            if($request->role_id ){
                $resource->assignRole(explode(',',$request->role_id));
            }

            return $this->sendResponse(new UserResource($resource), trans('user.User created Successfully'), );

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/user/{id}",
        * operationId="UserGet",
        * tags={"User"},
        * summary="Get User information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns User information",
        *  @OA\Parameter(
        *          name="id",
        *          description="User id",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer"
        *          )
        *      ),
        *      @OA\Response(
        *          response=201,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully",
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
    public function show($id)
    {
         try{

            $user = User::find($id);
            return $this->sendResponse(new UserResource($user), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/user/{id}",
     * operationId="UserUdpate",
     * tags={"User"},
     * summary="Update existing User",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns updated User data",
     *  @OA\Parameter(
    *          name="id",
    *          description="id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *    @OA\RequestBody(
     *         @OA\JsonContent(),
     *          @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email","phone","password", "country_code", "role_id"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit( 100)</small>)"),
     *               @OA\Property(property="email", type="text",description="Email (<small>char limit( 255)</small>)"),
     *               @OA\Property(property="phone", type="text",description="Phone (<small>char limit( 45)</small>)"),
     *               @OA\Property(property="country_code", type="integer",description="Country code"),
     *               @OA\Property(property="password", type="password",description="Password (<small>char limit( 255)</small>)"),
     *               @OA\Property(property="role_id", type="integer",description="Role ID"),
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="User updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User updated Successfully",
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
    public function update(UpdateUserRequest $request, $id)
    {
        try
        {
            $user = User::findOrFail($id);
            $user->update($request->all());

            if($request->role_id ){
                $user->syncRoles(explode(',',$request->role_id));
            }
            return $this->sendResponse(new UserResource($user), trans('user.User updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/user/{id}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Delete existing User",
    * security={
    *   {"passport": {}}
    * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="User deleted Successfully",
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
    public function destroy($id)
    {
        try
        {
            User::find($id)->delete();
            return $this->sendResponse([], trans('user.User deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

     /**
     * @OA\Post(
     *      path="/api/user/bulk-delete",
     *      operationId="deleteBulkUser",
     *      tags={"User"},
     *      summary="Delete existing Bulk User",
     *      security={
     *        {"passport": {}}
     *      },
     *      description="Deletes a record and returns no content",
     *      @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *
     *               @OA\Property(property="id", type="integer",description="ID"),
     *
     *
     *          ),
     *        ),
     *    ),
     *       @OA\Response(
     *          response=200,
     *          description="User deleted Successfully",
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
    public function bulkDelete(Request $request)
    {
        try
        {
             $ids = explode(",", $request->id);
             $orgIds = array_intersect(User::pluck('id')->toArray(), $ids);

             if (User::destroy($orgIds)) {
                 return $this->sendResponse([], trans('user.Users deleted Successfully'));
             }

             return $this->sendResponse([], 'User is not exists.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

}
