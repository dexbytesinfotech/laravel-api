<?php

namespace App\Http\Controllers\Api\Stores;

use App\Models\Stores\StoreType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Stores\StoreStoreTypeRequest;
use App\Http\Requests\Stores\UpdateStoreTypeRequest;
use App\Http\Resources\Stores\StoreTypeResource;
use App\Http\Resources\Stores\StoreTypeCollection;
use App\Http\Resources\Stores\StoreTypeMobileCollection;

class StoreTypeController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/store-type",
        * operationId="Store Type",
        * tags={"Store Type"},
        * summary="Get list of Store Type",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Store Type",
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
       return $this->sendResponse(StoreTypeCollection::collection(StoreType::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/store-type",
     * operationId="Store TypeStore",
     * tags={"Store Type"},
     * security={
     *   {"passport": {}}
     * },
     * summary="Store Store Type",
     * description="Returns Store Type data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Store Type created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Store Type created Successfully",
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
    public function store(StoreStoreTypeRequest $request, StoreType $storeType)
    {
          try
        {
            $resource = $storeType->create($request->all());
            return $this->sendResponse(new StoreTypeResource($resource), trans('store.Store Type created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/store-type/{id}",
        * operationId="Store TypeGet",
        * tags={"Store Type"},
        * security={
        *   {"passport": {}}
        * },
        * summary="Get Store Type information",
        * description="Returns Store Type information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Store Type id",
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
    public function show(StoreType $storeType)
    {
         try{
            return $this->sendResponse(new StoreTypeResource($storeType), 'Successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/store-type/{id}",
     * operationId="Store Type Udpate",
     * tags={"Store Type"},
     * security={
     *   {"passport": {}}
     * },
     * summary="Update existing Store Type",
     * description="Returns updated Store Type data",
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
    *         @OA\MediaType(
    *               mediaType="application/x-www-form-urlencoded",
    *               @OA\Schema(type="object", required={"name"},
    *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
    *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
    *            ),
    *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Store Type updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Store Type updated Successfully",
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
    public function update(UpdateStoreTypeRequest $request, $id)
    {
        try
        {   $storeType = StoreType::findOrFail($id);
            $storeType->update($request->all());
            return $this->sendResponse(new StoreTypeResource($storeType), trans('store.Store Type updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/store-type/{id}",
     *      operationId="deleteStore Type",
     *      tags={"Store Type"},
     * security={
     *   {"passport": {}}
     * },
     *      summary="Delete existing Store Type",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Store Type id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Store Type deleted Successfully",
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
    public function destroy(StoreType $StoreType)
    {
        try
        {
            $StoreType->delete();
            return $this->sendResponse([], trans('store.Store Type deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

     /**
        * @OA\Get(
        * path="/api/active-store-types",
        * operationId="Active Store Type ",
        * tags={"Store Type"},
        * summary="Active list of Store Type",
        * description="Returns list of Store Type",
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

    public function ActiveStoreType()
    {
        return $this->sendResponse(StoreTypeMobileCollection::collection(StoreType::whereStatus(1)->get()), 'Successfully.');
    }




}
