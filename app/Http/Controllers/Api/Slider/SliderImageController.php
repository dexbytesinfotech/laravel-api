<?php

namespace App\Http\Controllers\Api\Slider;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Slider\SliderImage;
use App\Http\Resources\Slider\SliderImageCollection;
use App\Http\Resources\Slider\SliderImageResource;
use App\Http\Requests\Slider\SliderImageStoreRequest;
use App\Http\Requests\Slider\SliderImageUpdateRequest;
use App\Http\Resources\Slider\SliderResource;
use App\Models\Slider\Slider;

class SliderImageController extends BaseController
{
    /**
        * @OA\Get(
        * path="/api/slider/image",
        * operationId="sliderImage",
        * tags={"Slider Image"},
        * summary="Get list of Slider Image",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Slider Image",
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
        return $this->sendResponse(SliderImageCollection::collection(SliderImage::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/slider/image",
     * operationId="sliderImageCreate",
     * tags={"Slider Image"},
     * summary="Store Slider Image",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns Slider Image data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"slider_id", "title", "image"},
     *               @OA\Property(property="slider_id", type="integer",description="Slider ID"),
     *               @OA\Property(property="title", type="text",description="Title (<small>char limit(100)</small>)"),
     *               @OA\Property(property="action_values", type="text",description="Action-Values"),
     *               @OA\Property(property="descriptions", type="text",description="Description"),
     *               @OA\Property(property="image", type="text", description="image to upload"),
     *               @OA\Property(property="status", type="integer",description="Status (0/1)")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Slider Image created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Slider Image created Successfully",
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
    public function store(SliderImageStoreRequest $request, SliderImage $sliderImage)
    {
        try
        {

            $sliderImageResource = $sliderImage->create($request->all());
            return $this->sendResponse(new SliderImageResource($sliderImageResource), trans('slider.Slider image created successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
        * @OA\Get(
        * path="/api/slider/image/{id}",
        * operationId="sliderImageGet",
        * tags={"Slider Image"},
        * summary="Get Slider Image information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Slider Image information",
        *  @OA\Parameter(
        *          name="id",
        *          description="slider image id",
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

            $sliderImage = SliderImage::find($id);
            return $this->sendResponse(new SliderImageResource($sliderImage), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }



    /**
     * @OA\Delete(
     *      path="/api/slider/image/{id}",
     *      operationId="deleteSliderImage",
     *      tags={"Slider Image"},
     *      summary="Delete existing slider image",
    * security={
    *   {"passport": {}}
    * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="slider image id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Slider Image deleted Successfully",
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
            SliderImage::find($id)->delete();
            return $this->sendResponse([], trans('slider.Slider image deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Post(
     *      path="/api/slider/image/bulk-delete",
     *      operationId="deleteBulkSliderImage",
     *      tags={"Slider Image"},
     *      summary="Delete existing Bulk slider Image",
     *      security={
     *        {"passport": {}}
     *      },
     *      description="Deletes a record and returns no content",
     *      @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="id", type="integer",description="ID"),
     *          ),
     *        ),
     *    ),
     *       @OA\Response(
     *          response=200,
     *          description="User deleted Successfully",
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
    public function bulkDelete(Request $request)
    {
        try
        {
             $ids = explode(",", $request->id);
             $orgIds = array_intersect(SliderImage::pluck('id')->toArray(), $ids);

             if (SliderImage::destroy($orgIds)) {
                 return $this->sendResponse([], trans('slider.Image deleted Successfully'));
             }

             return $this->sendResponse([], 'Image is not exists.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
        * @OA\Get(
        * path="/api/slider/image/group/{slider_id}",
        * operationId="sliderImageGetByGroupID",
        * tags={"Slider Image"},
        * summary="Get Slider Image information By Slider Id",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Slider Image information",
        *  @OA\Parameter(
        *          name="slider_id",
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
        public function sliderImagesByGroupID($slider_id)
        {
            try{

                $slider = Slider::with('sliderImage')->find($slider_id);
                return $this->sendResponse(new SliderResource($slider), 'Successfully.');

            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }

}
