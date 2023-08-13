<?php

namespace App\Http\Controllers\Api\Apis;

use App\Models\Apis\ApiProvider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Apis\StoreApiProviderRequest;
use App\Http\Requests\Apis\UpdateApiProviderRequest;
use App\Http\Resource\Apis\ApiProviderResource;
use App\Http\Resources\Apis\ApiProviderCollection as ApiProviderCollection;

class ApiProviderController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/apis/api-provider",
        * operationId="Api Provider",
        * tags={"Api Provider"},
        * summary="Get list of Api Provider",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Api Provider",
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
       return $this->sendResponse(ApiProviderCollection::collection(ApiProvider::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/apis/api-provider",
     * operationId="Api ProviderStore",
     * tags={"Api Provider"},
     * summary="Store Api Provider",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Api Provider data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "module", "code"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(60)</small>)"),
     *               @OA\Property(property="module", type="text",description="Module (<small>char limit(11)</small>)"),
     *               @OA\Property(property="code", type="text",description="Code (<small>char limit(30)</small>)"),
     *               @OA\Property(property="icon", type="text",description="Icon (<small>char limit(50)</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Api Provider created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Api Provider created Successfully",
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
    public function store(StoreApiProviderRequest $request, ApiProvider $apiProvider)
    {
          try
        {
            $resource = $apiProvider->create($request->all());
            return $this->sendResponse(new ApiProviderResource($resource), Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/apis/api-provider/{id}",
        * operationId="Api ProviderGet",
        * tags={"Api Provider"},
        * summary="Get Api Provider information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Api Provider information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Api Provider id",
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

            $apiProvider = ApiProvider::find($id);
            return $this->sendResponse(new ApiProviderResource($apiProvider), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/apis/api-provider/{id}",
     * operationId="Api ProviderUdpate",
     * tags={"Api Provider"},
     * summary="Update existing Api Provider",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated Api Provider data",
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
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "module", "code"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(60)</small>)"),
     *               @OA\Property(property="module", type="text",description="Module (<small>char limit(11)</small>)"),
     *               @OA\Property(property="code", type="text",description="Code (<small>char limit(30)</small>)"),
     *               @OA\Property(property="icon", type="text",description="Icon (<small>char limit(50)</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Api Provider updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Api Provider updated Successfully",
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
    public function update(UpdateApiProviderRequest $request, $id)
    {
        try
        {
            $apiProvider = ApiProvider::findOrFail($id);
            $apiProvider->update($request->all());
            return $this->sendResponse(new ApiProviderResource($apiProvider), Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/apis/api-provider/{id}",
     *      operationId="deleteApi Provider",
     *      tags={"Api Provider"},
     *      summary="Delete existing Api Provider",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Api Provider id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Api Provider deleted Successfully",
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
            ApiProvider::find($id)->delete();
            return $this->sendResponse([], trans('apiprovider.ApiProvider deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
