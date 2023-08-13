<?php

namespace App\Http\Controllers\Api\Pages;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Pages\StorePageRequest;
use App\Http\Requests\Pages\UpdatePageRequest;
use App\Http\Resources\Pages\PageResource;
use App\Http\Resources\Pages\PageCollection;
use Illuminate\Http\Response;
use App\Models\Posts\Post;
use Illuminate\Http\Request;

class PageController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/page",
        * operationId="Page",
        * tags={"Page"},
        * summary="Get list of Page",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Page",
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
       return $this->sendResponse(PageCollection::collection(Post::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/page",
     * operationId="PageStore",
     * tags={"Page"},
     * summary="Store Page",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns Page data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *               required={ "title","content"},
     *               @OA\Property(property="title", type="varchar", description="Page/Post Title ( <small>char limit-70</small>)"),
     *               @OA\Property(property="content", type="text",description="Page/Post Content (  <small>char limit-320</small>)"),
     *               @OA\Property(property="status", type="enum",description="Status ( <small>Vaild only ( 'draft', 'published', 'unpublished')</small>)"),
     *
     *           ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Page created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Page created Successfully",
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
    public function store(StorePageRequest $request, Post $page)
    {
        try
        {
            $validated = $request->validated();
            $validated['user_id'] = auth()->user()->id;
            $resource = $page->create($validated);
            return $this->sendResponse(new PageResource($resource), trans('page.Created Successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/page/{id}",
        * operationId="PageGet",
        * tags={"Page"},
        * summary="Get Page information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Page information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Page id",
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
            $page = Post::find($id);

            return $this->sendResponse(new PageResource($page), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/page/{id}",
     * operationId="PageUpdate",
     * tags={"Page"},
     * summary="Update existing Page",
    *  security={
    *   {"passport": {}}
    *  },
     * description="Returns updated Page data",
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
     *                required={"title","content"},
     *                 @OA\Property(property="title", type="varchar", description="Page/Post Title ( <small>char limit-70</small>)"),
     *                 @OA\Property(property="content", type="text",description="Page/Post Content (  <small>char limit-320</small>)"),
     *                 @OA\Property(property="status", type="enum",description="Status ( <small>Vaild only ( 'draft', 'published', 'unpublished')</small>)"),
     *
     *            ),
     *          ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Page updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Page updated Successfully",
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
    public function update(UpdatePageRequest $request,  Post $page)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth()->user()->id;
            $page->update($validated);

            return $this->sendResponse(new PageResource($page), trans('page.Page updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/page/{id}",
     *      operationId="deletePage",
     *      tags={"Page"},
     *      summary="Delete existing Page",
    *       security={
    *           {"passport": {}}
    *       },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Page id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Page deleted Successfully",
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
            Post::find($id)->delete();
            return $this->sendResponse([], trans('page.Page deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }


   /**
        * @OA\Get(
        * path="/api/page/get-by-slug/{slug}",
        * operationId="SlugGet",
        * tags={"Page"},
        * summary="Get slug information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Page information",
        *  @OA\Parameter(
        *          name="slug",
        *          description="Page slug",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="string"
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
        public function getSlug($slug)
        {

            try{
                if(Post::whereSlug($slug)->exists()){
                    return $this->sendResponse(PageCollection::collection( Post::with('user')->whereSlug($slug)->get()), 'Successfully.');
                }
                else{
                    return $this->sendResponse([], trans('page.Slug is not exists'));

                }


            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
            }
        }

}
