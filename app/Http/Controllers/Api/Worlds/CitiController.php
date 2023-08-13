<?php

namespace App\Http\Controllers\Api\Worlds;

use App\Models\Worlds\Citi;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Worlds\StoreCitiRequest;
use App\Http\Requests\Worlds\UpdateCitiRequest;
use App\Http\Resources\Worlds\CitiResource;
use App\Http\Resources\Worlds\CitiCollection;
use App\Http\Resources\Worlds\CountryByCitesResource;
use App\Models\Worlds\Cities;
use App\Models\Worlds\Country;
use Illuminate\Support\Facades\Response;


class CitiController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/worlds/citi",
        * operationId="Citi",
        * tags={"Locations"},
        * summary="Get list of Citi",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Citi",
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
       return $this->sendResponse(CitiCollection::collection(Cities::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/worlds/citi",
     * operationId="CitiStore",
     * tags={"Locations"},
     * summary="Store Citi",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Citi data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *              type="object",
     *               required={"name","country_id","state_id"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="country_id", type="integer",description="Country-Id"),
     *               @OA\Property(property="state_id", type="integer",description="State-Id"),
     *               @OA\Property(property="is_default", type="intger",description="Default"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Citi created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Citi created Successfully",
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
    public function store(StoreCitiRequest $request, Cities $citi)
    {
          try
        {
            $resource = $citi->create($request->all());
            return $this->sendResponse(new CitiResource($resource), trans('worlds.City created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/worlds/citi/{id}",
        * operationId="CitiGet",
        * tags={"Locations"},
        * summary="Get Citi information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Citi information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Citi id",
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

            $citi = Cities::find($id);
            return $this->sendResponse(new CitiResource($citi), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/worlds/citi/{id}",
     * operationId="CitiUdpate",
     * tags={"Locations"},
     * summary="Update existing Citi",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated Citi data",
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
     *                required={"name","country_id","state_id"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="country_id", type="integer",description="Country-Id"),
     *               @OA\Property(property="state_id", type="integer",description="State-Id"),
     *               @OA\Property(property="is_default", type="intger",description="Default"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Citi updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Citi updated Successfully",
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
    public function update(UpdateCitiRequest $request, $id)
    {
        try
        {   $citi = Cities::findOrFail($id);
            $citi->update($request->all());
            return $this->sendResponse(new CitiResource($citi), trans('worlds.City updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/worlds/citi/{id}",
     *      operationId="deleteCiti",
     *      tags={"Locations"},
     *      summary="Delete existing Citi",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Citi id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Citi deleted Successfully",
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
            Cities::find($id)->delete();
            return $this->sendResponse([], trans('worlds.City deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }


}
