<?php

namespace App\Http\Controllers\Api\Worlds;

use App\Models\Worlds\State;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Worlds\StoreStateRequest;
use App\Http\Requests\Worlds\UpdateStateRequest;
use App\Http\Resources\Worlds\CitiByStateResource;
use App\Http\Resources\Worlds\StateResource;
use App\Http\Resources\Worlds\StateCollection;
use App\Models\Worlds\Cities;
use Illuminate\Support\Facades\Response;

class StateController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/worlds/state",
        * operationId="State",
        * tags={"Locations"},
        * summary="Get list of State",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of State",
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
       return $this->sendResponse(StateCollection::collection(State::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/worlds/state",
     * operationId="StateStore",
     * tags={"Locations"},
     * summary="Store State",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns State data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","country_id"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="country_id", type="int",description="Country-ID "),
     *               @OA\Property(property="abbreviation", type="char" ,description="Abbrevation (<small>char-2</small>)"),
     *               @OA\Property(property="is_default", type="intger",description="Default"),
     *               @OA\Property(property="status", type="char",description="status (<small>char-1</small>)"),
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="State created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="State created Successfully",
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
    public function store(StoreStateRequest $request, State $state)
    {
          try
        {
            $resource = $state->create($request->all());
            return $this->sendResponse(new StateResource($resource), trans('worlds.State created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/worlds/state/{id}",
        * operationId="StateGet",
        * tags={"Locations"},
        * summary="Get State information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns State information",
        *  @OA\Parameter(
        *          name="id",
        *          description="State id",
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

            $state = State::find($id);
            return $this->sendResponse(new StateResource($state), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/worlds/state/{id}",
     * operationId="StateUdpate",
     * tags={"Locations"},
     * summary="Update existing State",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated State data",
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
     *              required={"name","country_id"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="country_id", type="int",description="Country-ID "),
     *               @OA\Property(property="abbreviation", type="char" ,description="Abbrevation (<small>char-2</small>)"),
     *               @OA\Property(property="is_default", type="intger",description="Default"),
     *               @OA\Property(property="status", type="char",description="status (<small>char-1</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="State updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="State updated Successfully",
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
    public function update(UpdateStateRequest $request, $id)
    {
        try
        {   $state = State::findOrFail($id);
            $state->update($request->all());
            return $this->sendResponse(new StateResource($state), trans('worlds.State updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/worlds/state/{id}",
     *      operationId="deleteState",
     *      tags={"Locations"},
     *      summary="Delete existing State",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="State id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="State deleted Successfully",
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
            State::find($id)->delete();
            return $this->sendResponse([], trans('worlds.State deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
        * @OA\Get(
        * path="/api/worlds/state/getCitiByState/{state_id}",
        * operationId="getCiti",
        * tags={"Locations"},
        * summary="Get list of citi by state",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Citi",
        *         @OA\Parameter(
        *          name="state_id",
        *          description="state id",
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

    public function citi($state_id){
        try{
            $data['citi'] = Cities::where("state_id",$state_id)->get(['id','name','state_id']);

            return $this->sendResponse(CitiByStateResource::collection($data['citi']) ,"suceesfully");
        }catch(\Exception $e){
            return $this->sendError($e->getMessage(), ['error'=> Response::json()]);
        }
    }
}
