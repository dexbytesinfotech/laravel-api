<?php

namespace App\Http\Controllers\Api\Media;

use App\Models\Media\Media;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Media\StoreMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Media\MediaCollection;
use Illuminate\Http\Response;
use Storage;

class MediaController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/media",
        * operationId="Media",
        * tags={"Media"},
        * summary="Get list of Media",
        * security={
        *    {"passport": {}}
        * },
        * description="Returns list of Media",
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
       return $this->sendResponse(MediaCollection::collection(Media::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/media",
     * operationId="MediaStore",
     * tags={"Media"},
     * summary="Store Media",
     * security={
     *    {"passport": {}}
     * },
     * description="Returns Media data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                required={"file", "collection_name"},
     *               @OA\Property(property="collection_name", type="text", description="collection name"),
     *               @OA\Property(property="file", type="file", description="file to upload"),
     *          ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Media created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Media created Successfully",
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
    public function store(StoreMediaRequest $request, Media $media)
    {
          try
        {
            $validated = $request->validated();
            $file = $request->file('file');
            $path = $file->store($validated['collection_name'], config('app_settings.filesystem_disk.value'));

            $payload = [];
            if(auth()->user()){
                $payload['user_id'] = auth()->user()->id;
            }
            $payload['collection_name'] = $validated['collection_name'];
            $payload['name'] = $file->getClientOriginalName();
            $payload['file_name'] = $path;
            $payload['disk'] = config('app_settings.filesystem_disk.value');
            $payload['size'] = $file->getSize(); // in bytes ;
            $payload['mime_type'] = $file->getClientMimeType();

            $resource = $media->create($payload);
            return $this->sendResponse(new MediaResource($resource), trans('media.Media created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/media/{id}",
        * operationId="MediaGet",
        * tags={"Media"},
        * summary="Get Media information",
        * security={
        *    {"passport": {}}
        * },
        * description="Returns Media information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Media id",
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

            $Media = Media::find($id);
            return $this->sendResponse(new MediaResource($Media), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/media/{id}",
     * operationId="MediaUdpate",
     * tags={"Media"},
     * summary="Update existing Media",
     * security={
     *    {"passport": {}}
     * },
     * description="Returns updated Media data",
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
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(type="object",
     *                required={"collection_name"},
     *                @OA\Property(property="collection_name", type="text",description="collection name"),
     *              )
     *          )
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Media updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Media updated Successfully",
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
    public function update(UpdateMediaRequest $request, $id)
    {
        try
        {   $Media = Media::findOrFail($id);
            $Media->update($request->all());
            return $this->sendResponse(new MediaResource($Media), trans('media.Media updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/media/{id}",
     *      operationId="deleteMedia",
     *      tags={"Media"},
     *      summary="Delete existing Media",
     *      security={
     *          {"passport": {}}
     *      },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Media id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Media deleted Successfully",
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
            Media::find($id)->delete();
            return $this->sendResponse([], trans('media.Media deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
