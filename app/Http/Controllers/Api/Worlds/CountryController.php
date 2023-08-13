<?php

namespace App\Http\Controllers\Api\Worlds;

use App\Models\Worlds\Country;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Worlds\StoreCountryRequest;
use App\Http\Requests\Worlds\UpdateCountryRequest;
use App\Http\Resources\Worlds\CitiesByCountryResource;
use App\Http\Resources\Worlds\CountryResource;
use App\Http\Resources\Worlds\CountryCollection;
use App\Http\Resources\Worlds\StateByCountryResource;
use App\Http\Resources\Worlds\StateResource;
use App\Models\Worlds\Cities;
use App\Models\Worlds\State;
use Illuminate\Support\Facades\Response;
//use Response;

class CountryController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/worlds/country",
        * operationId="Country",
        * tags={"Locations"},
        * summary="Get list of Country",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Country",
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
       return $this->sendResponse(CountryCollection::collection(Country::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/worlds/country",
     * operationId="CountryStore",
     * tags={"Locations"},
     * summary="Store Country",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Country data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *              type="object",
     *               required={"name","country_ios_code"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="country_ios_code", type="char",description="Country-IOS-Code (<small>char-2</small>)"),
     *               @OA\Property(property="nationality", type="text",description="Nationality (<small>char limit(100)</small>)"),
     *               @OA\Property(property="is_default", type="intger",description="Default"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Country created Successfully",
     *          @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Country created Successfully",
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
    public function store(StoreCountryRequest $request, Country $country)
    {
          try
        {
            $resource = $country->create($request->all());
            return $this->sendResponse(new CountryResource($resource), trans('worlds.Country created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/worlds/country/{id}",
        * operationId="CountryGet",
        * tags={"Locations"},
        * summary="Get Country information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Country information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Country id",
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

            $country = Country::find($id);
            return $this->sendResponse(new CountryResource($country), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/worlds/country/{id}",
     * operationId="CountryUdpate",
     * tags={"Locations"},
     * summary="Update existing Country",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated Country data",
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
     *               required={"name","country_ios_code"},
     *                @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="country_ios_code", type="char",description="Country-IOS-Code (<small>char-2</small>)"),
     *               @OA\Property(property="nationality", type="text",description="Nationality (<small>char limit(100)</small>)"),
     *               @OA\Property(property="is_default", type="intger",description="Default"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Country updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Country updated Successfully",
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
    public function update(UpdateCountryRequest $request, $id)
    {
        try
        {   $country = Country::findOrFail($id);
            $country->update($request->all());
            return $this->sendResponse(new CountryResource($country), trans('worlds.Country updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/worlds/country/{id}",
     *      operationId="deleteCountry",
     *      tags={"Locations"},
     *      summary="Delete existing Country",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Country id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Country deleted Successfully",
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
            Country::find($id)->delete();
            return $this->sendResponse([], trans('worlds.Country deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

     /**
        * @OA\Get(
        * path="/api/worlds/country/getStateByCountry/{country_id}",
        * operationId="getState",
        * tags={"Locations"},
        * summary="Get list of state by country",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of State",
        *        @OA\Parameter(
        *          name="country_id",
        *          description="Country id",
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

    public function state($country_id){
        try{
            $data['state'] = State::where("country_id",$country_id)->get(['id','name','country_id']);

            return $this->sendResponse(StateByCountryResource::collection($data['state']) ,"suceesfully");
        }catch(\Exception $e){
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

     /**
        * @OA\Get(
        * path="/api/worlds/country/getCityByCountry/{country_id}",
        * operationId="getCitiByCountry",
        * tags={"Locations"},
        * summary="Get list of country by citi",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Country",
        *        @OA\Parameter(
        *          name="country_id",
        *          description="country_id",
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


        public function citi($country_id){
            try{
                $data['citi'] = Cities::where("country_id",$country_id)->get(['id','name','country_id']);

                return $this->sendResponse(CitiesByCountryResource::collection($data['citi']) ,"suceesfully");
            }catch(\Exception $e){
                return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
            }
        }
}
