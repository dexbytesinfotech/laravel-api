<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\Roles\RoleResource;
use App\Http\Resources\Roles\RoleCollection;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/role",
        * operationId="Role",
        * tags={"Role"},
        * summary="Get list of Role",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Role",
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
       return $this->sendResponse(RoleCollection::collection(Role::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/role",
     * operationId="RoleStore",
     * tags={"Role"},
     * summary="Store Role",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Role data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","guard_name"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="guard_name", type="text",description="Guard-name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="permission_id", type="integer",description="Permission ID"),
     *                  @OA\Property(property="status", type="string",description="Status (<small>char-1</small>)"),
     *                  @OA\Property(property="content", type="text",description="Content"),
     *
     *
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Role created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Role created Successfully",
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
    public function store(StoreRoleRequest $request, Role $role)
    {
        try
        {
            $permission_id = $request->input('permission_id');
            $payload = $request->all();
            print_r($payload);die;
            unset($payload['permission_id']);
            $resource = $role->create($payload);

            if($permission_id){
                $resource->syncPermissions(explode(',',$permission_id));
            }

            return $this->sendResponse(new RoleResource($resource), trans('role.Role created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/role/{id}",
        * operationId="RoleGet",
        * tags={"Role"},
        * summary="Get Role information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Role information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Role id",
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
            $role = Role::find($id);
            return $this->sendResponse(new RoleResource($role), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/role/{id}",
     * operationId="RoleUdpate",
     * tags={"Role"},
     * summary="Update existing Role",
    *  security={
    *   {"passport": {}}
    *  },
     * description="Returns updated Role data",
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
     *           @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"name" ,"guard_name"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="guard_name", type="text",description="Guard-name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="permission_id", type="integer",description="Permission ID"),
     *               @OA\Property(property="status", type="string",description="Status (<small>char-1</small>)"),
     *               @OA\Property(property="content", type="text",description="Content"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Role updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Role updated Successfully",
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
    public function update(UpdateRoleRequest $request, $id)
    {
        try
        {   $role = Role::findOrFail($id);
            $role->update($request->all());
            if($request->permission_id){
                    $role->syncPermissions(explode(',',$request->permission_id));

            }
            return $this->sendResponse(new RoleResource($role), trans('role.Role updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/role/{id}",
     *      operationId="deleteRole",
     *      tags={"Role"},
     *      summary="Delete existing Role",
    *       security={
    *           {"passport": {}}
    *       },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Role deleted Successfully",
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
            Role::find($id)->delete();
            return $this->sendResponse([], trans('role.Role deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
