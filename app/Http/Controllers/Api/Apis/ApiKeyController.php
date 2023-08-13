<?php

namespace App\Http\Controllers\Api\Apis;


use App\Models\Apis\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Apis\StoreApiKeyRequest;
use App\Http\Requests\Apis\UpdateApiKeyRequest;
use App\Http\Resources\Apis\ApiKeyResource;
use App\Http\Resources\Apis\ApiKeyCollection;

class ApiKeyController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/apis/api-key",
        * operationId="Api Key",
        * tags={"Api Key"},
        * summary="Get list of Api Key",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Api Key",
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
       return $this->sendResponse(ApiKeyCollection::collection(ApiKey::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/apis/api-key",
     * operationId="Api KeyStore",
     * tags={"Api Key"},
     * summary="Store Api Key",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Api Key data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"provider_id","key","value"},
     *               @OA\Property(property="provider_id", type="number",description="provider Id"),
     *               @OA\Property(property="key", type="text",description="key (<small>char limit(30)</small>)"),
     *               @OA\Property(property="value", type="text",description="value (<small>char limit(255)</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Api Key created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Api Key created Successfully",
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
    public function store(StoreApiKeyRequest $request, ApiKey $apiKey)
    {
          try
        {
            $resource = $apiKey->create($request->all());
            return $this->sendResponse(new ApiKeyResource($resource), Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/apis/api-key/{id}",
        * operationId="Api KeyGet",
        * tags={"Api Key"},
        * summary="Get Api Key information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Api Key information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Api Key id",
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

            $apiKey = ApiKey::find($id);
            return $this->sendResponse(new ApiKeyResource($apiKey), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/apis/api-key/{id}",
     * operationId="Api KeyUdpate",
     * tags={"Api Key"},
     * summary="Update existing Api Key",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated Api Key data",
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
     *               required={"provider_id"},
     *                @OA\Property(property="provider_id", type="number",description="provider Id"),
     *               @OA\Property(property="key", type="text",description="key (<small>char limit(30)</small>)"),
     *               @OA\Property(property="value", type="text",description="value (<small>char limit(255)</small>)"),
     *               @OA\Property(property="icon", type="text",description="icon"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Api Key updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Api Key updated Successfully",
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
    public function update(UpdateApiKeyRequest $request, $id)
    {
        try
        {   $apiKey = ApiKey::findOrFail($id);
            $apiKey->update($request->all());
            return $this->sendResponse(new ApiKeyResource($apiKey), trans('apiprovider.ApiKey updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/apis/api-key/{id}",
     *      operationId="deleteApi Key",
     *      tags={"Api Key"},
     *      summary="Delete existing Api Key",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Api Key id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Api Key deleted Successfully",
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
            ApiKey::find($id)->delete();
            return $this->sendResponse([], trans('apiprovider.ApiKey deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
