<?php

namespace App\Http\Controllers\Api\Slider;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Slider\Slider;
use App\Http\Resources\Slider\SliderCollection;
use App\Http\Resources\Slider\SliderResource;
use App\Http\Requests\Slider\SliderStoreRequest;
use App\Http\Requests\Slider\SliderUpdateRequest;

class SliderController extends BaseController
{
    /**
        * @OA\Get(
        * path="/api/slider/",
        * operationId="slider",
        * tags={"Slider"},
        * summary="Get list of Slider",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Slider",
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
        return $this->sendResponse(SliderCollection::collection(Slider::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/slider/",
     * operationId="sliderCreate",
     * tags={"Slider"},
     * summary="Store Slider",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns Slider data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="description", type="text",description="Description"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *               @OA\Property(property="is_default", type="integer",description="is-default (<small> (Valid Only 0 and 1)</small>)"),
     *               @OA\Property(property="start_date_time", type="string", format ="date-time",description="Start-Date-Time (<small> Y-M-D H-M-S</small>)"),
     *               @OA\Property(property="end_date_time", type="string",  format ="date-time",description="End-Date-Time (<small> Y-M-D H-M-S</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Slider created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Slider created Successfully",
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
    public function store(SliderStoreRequest $request, Slider $slider)
    {
        try
        {
            $sliderResource = $slider->create($request->all());
            return $this->sendResponse(new SliderResource($sliderResource), trans('slider.Slider created successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
        * @OA\Get(
        * path="/api/slider/{id}",
        * operationId="sliderGet",
        * tags={"Slider"},
        * summary="Get Slider information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Slider information",
        *  @OA\Parameter(
        *          name="id",
        *          description="slider id",
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
    public function show(Slider $slider)
    {
        return $this->sendResponse(new SliderResource($slider), 'Successfully.');
    }

    /**
     * @OA\Put(
     * path="/api/slider/{id}",
     * operationId="sliderUpdate",
     * tags={"Slider"},
     * summary="Update existing Slider",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns updated Slider data",
     *  @OA\Parameter(
    *          name="id",
    *          description="slider id",
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
     *               @OA\Property(property="description", type="text",description="Description"),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char-1</small>)"),
     *               @OA\Property(property="is_default", type="integer",description="is-default (<small> (Valid Only 0 and 1)</small>)"),
     *               @OA\Property(property="start_date_time", type="string", example="2023-01-15 10:00:00", format ="date-time",description="Start-Date-Time (<small> Y-M-D H-M-S</small>)"),
     *               @OA\Property(property="end_date_time", type="string", example="2023-01-30 20:00:00", format ="date-time",description="End-Date-Time (<small> Y-M-D H-M-S</small>)"),
     *
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Slider updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Slider updated Successfully",
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
    public function update(SliderUpdateRequest $request, Slider $slider)
    {
        try
        {
            $slider->update($request->all());
            return $this->sendResponse(new SliderResource($slider), trans('slider.Slider updated successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

      /**
     * @OA\Delete(
     *      path="/api/slider/{id}",
     *      operationId="deleteSlider",
     *      tags={"Slider"},
     *      summary="Delete existing slider",
    * security={
    *   {"passport": {}}
    * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="slider id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Slider deleted Successfully",
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
    public function destroy(Slider $slider)
    {
        try
        {
            $slider->delete();
            return $this->sendResponse([], trans('slider.Slider deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
