<?php

namespace App\Http\Controllers\Api\Stores;

use Illuminate\Http\Request;
use App\Models\Stores\Store;
use App\Models\Stores\StoreAddress;
use App\Models\Stores\StoreMetaData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Stores\StoreStoreRequest;
use App\Http\Requests\Stores\UpdateStoreRequest;
use App\Http\Resources\Stores\StoreResource;
use App\Http\Resources\Stores\StoreCollection;
use Illuminate\Http\Response;
use App\Models\User;
use App\Http\Resources\Users\UserCollection;

class StoreController extends BaseController
{
    public $storeAddressModel;
    public $storeMetaModel;

    public function __construct()
    {
        $this->storeAddressModel = new StoreAddress;
        $this->storeMetaModel = new StoreMetaData;
    }

     /**
        * @OA\Get(
        * path="/api/store",
        * operationId="Store",
        * tags={"Store"},
        * security={
        *   {"passport": {}}
        * },
        * summary="Get list of Store",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Store",
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
        $stores = Store::paginate(10);
        $pagination = [
            "current_page" => (integer) $stores->currentPage(),
            "prev_page_api_url" => (string) $stores->previousPageUrl(),
            "next_page_api_url" => (string) $stores->nextPageUrl(),
            "last_page" => (integer) $stores->lastPage(),
            "per_page" => (integer) $stores->perPage(),
            "total" => (integer) $stores->total()
        ];

       return $this->sendResponse(StoreCollection::collection($stores), 'Successfully.', $pagination);
    }

    /**
     * @OA\POST(
     * path="/api/store",
     * operationId="StoreStore",
     * tags={"Store"},
     * security={
     *   {"passport": {}}
     * },
     * summary="Insert Store Data",
     * description="Returns Store data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "phone","country_code", "email", "address_line_1", "landmark", "country", "zip_post_code", "latitude", "longitude"},
     *               @OA\Property(property="country_code", type="text", description="Country Code"),
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit( 50)</small>)"),
     *               @OA\Property(property="descriptions", type="text", description="Descriptions"),
     *               @OA\Property(property="phone", type="numeric",description="Phone No."),
     *               @OA\Property(property="email", type="text",description="Email"),
     *               @OA\Property(property="content", type="text",description="Content"),
     *               @OA\Property(property="number_of_branch", type="integer",description="Number Of Branches"),
     *               @OA\Property(property="logo_path", type="text",description="Logo Image Path"),
     *               @OA\Property(property="background_image_path", type="text",description="Background Image Path"),
     *               @OA\Property(property="address_line_1", type="text", description="Address (<small>char limit( 100)</small>)" ),
     *               @OA\Property(property="landmark", type="text", description="Address Landmark (<small>char limit( 100)</small>)" ),
     *               @OA\Property(property="city", type="text", description="City (<small>char limit( 50)</small>)" ),
     *               @OA\Property(property="state", type="text", description="State (<small>char limit( 50)</small>)" ),
     *               @OA\Property(property="country", type="text", description="Country (<small>char limit( 50)</small>)" ),
     *               @OA\Property(property="zip_post_code", type="text", description="Zip Code (<small>char limit( 11)</small>)" ),
     *               @OA\Property(property="latitude", type="text", description="Latitude (<small>char limit(min 8, Max 11)</small>)" ),
     *               @OA\Property(property="longitude", type="text", description="Longitude (<small>char limit(min 8, Max 11)</small>)" ),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Store created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Store created Successfully",
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
    public function store(StoreStoreRequest $request, Store $store)
    {
        try
        {
            $resource = $store->create($request->all());
            $request->merge(['store_id'=> $resource->id]);
            $address = $this->storeAddressModel->create($request->all());

            $defaultBusinessHours =  collect(json_decode($store->getDefaultBusinessHours(),true))->map(function ($value,$key) use($resource)
            {
                $value['store_id'] = $resource->id;
                $value['created_at'] = \Carbon\Carbon::now();
                return $value;
            })->all();
            \App\Models\Stores\BusinessHour::insert($defaultBusinessHours);

            return $this->sendResponse(new StoreResource($resource), trans('store.Store created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
    * @OA\Get(
    * path="/api/store/{id}",
    * operationId="StoreGet",
    * tags={"Store"},
    * security={
    *   {"passport": {}}
    * },
    * summary="Get Store information",
    * description="Returns Store information",
    *  @OA\Parameter(
    *          name="id",
    *          description="Store id",
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

            $store = Store::find($id);
            if($store)
            {
                return $this->sendResponse(new StoreResource($store), 'Successfully.');
            }
            return $this->sendResponse([], trans('store.Store is not exists'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/store/{id}",
     * operationId="StoreUdpate",
     * tags={"Store"},
     * security={
     *   {"passport": {}}
     * },
     * summary="Update existing Store",
     * description="Returns updated Store data",
     *
    * security={
    *   {"passport": {}}
    * },
     *
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"name", "phone", "email", "address_line_1", "landmark", "country", "zip_post_code", "latitude", "longitude"},
    *               @OA\Property(property="name", type="text",description="Name (<small>char limit( 50)</small>)"),
     *               @OA\Property(property="descriptions", type="text", description="Descriptions"),
     *               @OA\Property(property="phone", type="numeric",description="Phone No."),
     *               @OA\Property(property="email", type="text",description="Email"),
     *               @OA\Property(property="content", type="text",description="Content"),
     *               @OA\Property(property="number_of_branch", type="integer",description="Number Of Branches"),
     *               @OA\Property(property="logo_path", type="text",description="Logo Image Path"),
     *               @OA\Property(property="background_image_path", type="text",description="Background Image Path"),
     *               @OA\Property(property="address_line_1", type="text", description="Address (<small>char limit( 100)</small>)" ),
     *               @OA\Property(property="landmark", type="text", description="Address Landmark (<small>char limit( 100)</small>)" ),
     *               @OA\Property(property="city", type="text", description="City (<small>char limit( 50)</small>)" ),
     *               @OA\Property(property="state", type="text", description="State (<small>char limit( 50)</small>)" ),
     *               @OA\Property(property="country", type="text", description="Country (<small>char limit( 50)</small>)" ),
     *               @OA\Property(property="zip_post_code", type="text", description="Zip Code (<small>char limit( 11)</small>)" ),
     *               @OA\Property(property="latitude", type="text", description="Latitude (<small>char limit(min 8, Max 11)</small>)" ),
     *               @OA\Property(property="longitude", type="text", description="Longitude (<small>char limit(min 8, Max 11)</small>)" ),
    *            ),
    *        ),
    *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Store updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Store updated Successfully",
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
    public function update(UpdateStoreRequest $request, $id)
    {
        try
        {   $store = Store::findOrFail($id);

            $store->update($request->all());
            $address = $this->storeAddressModel->update($request->all());


            return $this->sendResponse(new StoreResource($store), trans('store.Store updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**

    * @OA\Delete(
    *   path="/api/store/{id}",
    *   operationId="deleteStore",
    *   tags={"Store"},
    *   summary="Delete existing Store",
    *   description="Deletes a record and returns no content",
    *   security={
    *       {"passport": {}}
    *   },
    *   @OA\RequestBody(
    *      @OA\JsonContent(),
    *      @OA\MediaType(
    *          mediaType="multipart/form-data",
    *          @OA\Schema(
    *              type="object",
    *              required={"id"},
    *              @OA\Property(property="id", type="integer")
    *           ),
    *       ),
    *   ),
    *   @OA\Response(
    *      response=200,
    *       description="Store deleted Successfully",
    *       @OA\JsonContent()
    *   ),
    *   @OA\Response(
    *       response=204,
    *       description="Successful operation",
    *       @OA\JsonContent()
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Unauthenticated",
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden"
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="Resource Not Found"
    *   )
    * )
    */
    public function destroy($id)
    {
        try
        {
            $store = Store::find($id);
            if($store)
            {
                $store->delete();
                return $this->sendResponse([], trans('store.Store deleted Successfully'));
            }
            else
            {
                return $this->sendError(trans('store.Resource Not Found'), ['error'=> Response::HTTP_NOT_FOUND], 403);
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
    /**
     *
     *  @OA\Post(
     *      path="/api/sotre/bulk-delete",
     *      operationId="deleteBulkStores",
     *      tags={"Store"},
     *      security={
     *          {"passport": {}}
     *      },
     *      summary="Delete existing Bulk Stores",
     *      description="Delete records and returns no content",
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",     *
     *                  @OA\Property(property="id", type="integer",description="Comma separated IDs"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Stores deleted Successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      ),
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
    public function bulkDelete(Request $request )
    {
        try
        {
            $ids = explode(",", $request->id);
            $orgIds = array_intersect(Store::pluck('id')->toArray(), $ids);
            if (Store::destroy($orgIds)) {
                return $this->sendResponse([], trans('store.Stores deleted Successfully'));
            }

            return $this->sendResponse([], trans('store.Store is not exists'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

}
