<?php

namespace App\Http\Controllers\Api\Stores;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Response;
use App\Http\Requests\Stores\CheckStoreIdRequest;
use App\Http\Requests\Stores\UpdateBusinessHoursRequest;
use App\Http\Resources\Stores\StoreBusinessHoursCollection;
use App\Models\Stores\Store;

class BusinessHoursController extends BaseController
{


        /**
     *  @OA\Post(
     *      path="/api/store/business-hours",
     *      operationId="getStoreBusinessHours",
     *      tags={"Store"},
     *      summary="Get business hours of the Store. ",
     *      description="Returns Store Business Hours",
     *      security={
     *          {"passport": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",     
     *                  required={"store_id"},
     *                  @OA\Property(property="store_id", type="integer",description="Store ID"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully",
     *           @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function getBusinessHours(CheckStoreIdRequest $request)
    {
        try
        {
            $store = Store::find($request->get('store_id'));
            return $this->sendResponse(StoreBusinessHoursCollection::collection($store->BusinessHour), 'Successfully.');
      
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }




        /**
     *  @OA\Post(
     *      path="/api/store/update-business-hours",
     *      operationId="updateStoreBusinessHours",
     *      tags={"Store"},
     *      summary="Update business hours of the Store. ",
     *      description="Returns Store Business Hours",
     *      security={
     *          {"passport": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",     
     *                  required={"store_id", "business_hours"},
     *                  @OA\Property(property="store_id", type="integer",description="Store ID"),
     *                  @OA\Property(property="business_hours", type="JSON",description="Business hours JSON"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *  )
     */
    public function updateBusinessHours(UpdateBusinessHoursRequest $request)
    {
        try
        {   
            $store_id = $request->get('store_id');
            $business_hours = $request->get('business_hours');
            $store = Store::find($store_id);
            $store->metadata->updateOrCreate(['store_id' => $store_id, 'key' => 'business_hours'], ['value'=> $business_hours]);

            return $this->sendResponse(StoreBusinessHoursCollection::collection($store->BusinessHour), 'Successfully.');
    
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

}