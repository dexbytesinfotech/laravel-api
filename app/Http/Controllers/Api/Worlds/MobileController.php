<?php

namespace App\Http\Controllers\Api\Worlds;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worlds\StoreMobileCityRequest;
use App\Http\Resources\Worlds\CitiByStateResource;
use App\Http\Resources\Worlds\CountryMobileCollection;
use App\Http\Resources\Worlds\StateByCountryResource;
use App\Models\Worlds\Cities;
use App\Models\Worlds\Country;
use App\Models\Worlds\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MobileController extends BaseController
{
   /**
        * @OA\Get(
        * path="/api/worlds/mobile/country",
        * operationId="MobileCountry",
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
           return $this->sendResponse(CountryMobileCollection::collection(Country::wherestatus(1)->get()), __('worlds.Successfully.'));
        }

         /**
        * @OA\Get(
        * path="/api/worlds/mobile/states/{country_id}",
        * operationId="MobilegetState",
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
          
            $states= State::whereStatus(1)->where("country_id", $country_id)->get(['id','name','country_id']);
            return $this->sendResponse(StateByCountryResource::collection($states) ,__("worlds.sucessfully"));
        
        }catch(\Exception $e){
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
/**
        * @OA\post(
        * path="/api/worlds/mobile/cities ",
        * operationId="MobilegetCiti",
        * tags={"Locations"},
        * summary="Get list of citi by state",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Citi",
        *          @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *              type="object",
        *               required={ },          
        *               @OA\Property(property="state_id", type="integer",description="state id"),
        *               @OA\Property(property="country_id", type="integer",description="Country-Id"),
        *    ),
        *        ),
        *      ),
        *
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

        public function city(StoreMobileCityRequest $request){
            try{
                if( $request->state_id){
                     $cities = Cities::wherestatus(1)->wherestate_id($request->state_id)->get(['id','name','state_id','country_id']);     
                } else {
                     $cities = Cities::wherestatus(1)->wherecountry_id($request->country_id)->get(['id','name','country_id','state_id']);
                }
                
                return $this->sendResponse(CitiByStateResource::collection($cities) ,__("worlds.suceesfully."));
            
            }catch(\Exception $e){
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }
}
