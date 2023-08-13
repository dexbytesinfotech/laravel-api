<?php

namespace App\Http\Controllers\Api\Roles;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Roles\StorePermissionRequest;
use App\Http\Requests\Roles\UpdatePermissionRequest;
use App\Http\Resources\Roles\PermissionResource;
use App\Http\Resources\Roles\PermissionCollection;

class PermissionController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/roles/permission",
        * operationId="Permission",
        * tags={"Permission"},
        * summary="Get list of Permission",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Permission",
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
       return $this->sendResponse(PermissionCollection::collection(Permission::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/roles/permission",
     * operationId="PermissionStore",
     * tags={"Permission"},
     * summary="Store Permission",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns Permission data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *              type="object",
     *               required={"name","guard_name"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="guard_name", type="text",description="Guard-Name (<small>char limit(100)</small>)")
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Permission created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Permission created Successfully",
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
    public function store(StorePermissionRequest $request, Permission $permission)
    {
          try
        {
            $resource = $permission->create($request->all());
            return $this->sendResponse(new PermissionResource($resource), trans('role.Permission created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/roles/permission/{id}",
        * operationId="PermissionGet",
        * tags={"Permission"},
        * summary="Get Permission information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Permission information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Permission id",
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

            $permission = Permission::find($id);
            return $this->sendResponse(new PermissionResource($permission), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/roles/permission/{id}",
     * operationId="PermissionUdpate",
     * tags={"Permission"},
     * summary="Update existing Permission",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns updated Permission data",
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
     *               required={"name" ,"guard_name"},
     *              @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="guard_name", type="text",description="Guard-Name (<small>char limit(100)</small>)")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Permission updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Permission updated Successfully",
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
    public function update(UpdatePermissionRequest $request, $id)
    {
        try
        {   $permission = Permission::findOrFail($id);
            $permission->update($request->all());
            return $this->sendResponse(new PermissionResource($permission), trans('role.Permission updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/roles/permission/{id}",
     *      operationId="deletePermission",
     *      tags={"Permission"},
     *      summary="Delete existing Permission",
    * security={
    *   {"passport": {}}
    * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Permission id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Permission deleted Successfully",
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
            Permission::find($id)->delete();
            return $this->sendResponse([], trans('role.Permission deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
