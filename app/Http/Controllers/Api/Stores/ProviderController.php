<?php

namespace App\Http\Controllers\Api\Stores;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Response;
use App\Http\Requests\Stores\StoreProviderRequest;
use App\Models\Stores\StoreOwners;
use App\Http\Resources\Stores\ProviderCollection;
use App\Models\User;

class ProviderController extends BaseController
{

   /**
     *  @OA\Post(
     *       path="/api/store/get-all-providers",
     *       operationId="getProviderUsers",
     *      tags={"Store"},
     *      summary="Get list of Provider Users",
     *       description="Returns list of Provider Users",
     *      security={
     *          {"passport": {}}
     *      },
    *   @OA\Response(
    *       response=201,
    *       description="Successfully",
    *       @OA\JsonContent()
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Successfully",
    *       @OA\JsonContent()
    *   ),
    *   @OA\Response(
    *       response=422,
    *       description="Unprocessable Entity",
    *       @OA\JsonContent()
    *   ),
    *   @OA\Response(response=400, description="Bad request"),
    *   @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
    public function getAllProvider()
    {
        try
        {
            $users = User::whereHas(
                'roles', function($q) {
                    $q->where('name', 'Provider');
                }
            )->get();

            return $this->sendResponse(ProviderCollection::collection($users), 'Successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/store/add-provider",
     *      operationId="addStoreManager",
     *      tags={"Store"},
     *      summary="Assign a Manager to the Store. ",
     *      description="Returns Store With Provider User",
     *      security={
     *          {"passport": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"store_id", "user_id"},
     *                  @OA\Property(property="store_id", type="integer",description="Store ID"),
     *                  @OA\Property(property="user_id", type="integer",description="User ID"),
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
    public function storeProvider(StoreProviderRequest $request)
    {
        try
        {
            $storeId = (integer) $request->get('store_id');
            $userId = (integer) $request->get('user_id');

            $user = User::find($userId);

            if(!$user->hasAnyRole(['Provider'])){
                return $this->sendError(trans('store.Please store provider with provider privileges and try again'));
            }

            $isExist = StoreOwners::where("user_id", $userId)->first();
            if($isExist && $isExist->store_id !==  $storeId){
                return $this->sendError(trans('store.This provider is already registered in the another restaurant'));
            }

            $payload = [
                'store_id'  => $storeId,
                'user_id'  => $userId,
            ];

            $provier = StoreOwners::updateOrCreate(
                    $payload, $payload
            );

             return $this->sendResponse($payload, trans('store.Provider added Successfully'));

            } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }


        /**
     *  @OA\Post(
     *      path="/api/store/remove-provider",
     *      operationId="removeStoreManager",
     *      tags={"Store"},
     *      summary="Remove a Provider to the Store. ",
     *      description="Returns Store With Provider User",
     *      security={
     *          {"passport": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"store_id", "user_id"},
     *                  @OA\Property(property="store_id", type="integer",description="Store ID"),
     *                  @OA\Property(property="user_id", type="integer",description="User ID"),
     *              ),
     *          ),
     *      ),
    *       @OA\Response(
    *           response=201,
    *           description="Successfully",
    *           @OA\JsonContent()
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="Successfully",
    *           @OA\JsonContent()
    *       ),
    *       @OA\Response(
    *           response=422,
    *           description="Unprocessable Entity",
    *           @OA\JsonContent()
    *       ),
    *       @OA\Response(response=400, description="Bad request"),
    *       @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
    public function removeProvider(StoreProviderRequest $request)
    {
        try
        {
            $payload = [
                'store_id'  => $request->get('store_id'),
                'user_id'  => $request->get('user_id'),
            ];

            $provier = StoreOwners::where($payload)->get();
            if(!$provier){
                return $this->sendError(trans('store.Provider or Store does not exists'));
            }

            $provier->delete();

            return $this->sendResponse($payload, trans('store.Provider deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }


}
