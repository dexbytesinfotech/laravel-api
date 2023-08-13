<?php

namespace App\Http\Controllers\Api\Faq;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Faq\Faq;
use App\Http\Resources\Faq\FaqCollection;
use App\Http\Resources\Faq\FaqResource;
use App\Http\Requests\Faq\FaqStoreRequest;
use App\Http\Requests\Faq\FaqUpdateRequest;

class FaqController extends BaseController
{
    public function __construct()
    {
        //
    }

       /**
        * @OA\Get(
        * path="/api/faq",
        * operationId="faq",
        * tags={"FAQ"},
        * summary="Get list of Faq",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Faq",
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
        return $this->sendResponse(FaqCollection::collection(Faq::with('category')->get()), 'Successfully.');
    }

       /**
     * @OA\POST(
     * path="/api/faq/",
     * operationId="faqCreate",
     * tags={"FAQ"},
     * summary="Store Faq",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Faq data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"title", "descriptions", "faq_category_id",},
     *               @OA\Property(property="title", type="text",description="Title (<small>char limit(100)</small>)"),
     *               @OA\Property(property="descriptions", type="text",description="Description "),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char limit(20)</small>)"),
     *               @OA\Property(property="role_type",description="Role Type  (<small> Valid only ( provider/customer)</small>)",type="string",),
     *               @OA\Property(property="faq_category_id", type="integer",description="Faq Category ID "),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="FAQ created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="FAQ created Successfully",
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
    public function store(FaqStoreRequest $request, Faq $faq)
    {
        try
        {
            $faqResource = $faq->create($request->all());
            return $this->sendResponse(new FaqResource($faqResource), Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

     /**
        * @OA\Get(
        * path="/api/faq/{id}",
        * operationId="faqGet",
        * tags={"FAQ"},
        * summary="Get Faq information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Faq information",
        *  @OA\Parameter(
        *          name="id",
        *          description="faq id",
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
    public function show(Faq $faq)
    {

        return $this->sendResponse(new FaqResource($faq), 'Successfully.');
    }

      /**
     * @OA\Put(
     * path="/api/faq/{id}",
     * operationId="faqUdpate",
     * tags={"FAQ"},
     * summary="Update existing Faq",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated Faq data",
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
     *         @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(type="object", required={"title", "descriptions", "faq_category_id"},
     *               @OA\Property(property="title", type="text",description="Title (<small>char limit(100)</small>)"),
     *               @OA\Property(property="descriptions", type="text",description="Description "),
     *               @OA\Property(property="status", type="integer",description="Status (<small>char limit(20)</small>)"),
     *                @OA\Property(property="role_type",description="Role Type  (<small> Valid only ( provider/customer)</small>)",type="string",),
     *               @OA\Property(property="faq_category_id", type="integer",description="Faq Category ID "),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="FAQ updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="FAQ updated Successfully",
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
    public function update(FaqUpdateRequest $request, $id)
    {
        try
        {
            $faq = Faq::findOrFail($id);
            $faq->update($request->all());
            return $this->sendResponse(new FaqResource($faq), Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }


   /**
     * @OA\Delete(
     *      path="/api/faq/{id}",
     *      operationId="deletefaq",
     *      tags={"FAQ"},
     *      summary="Delete existing faq",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="faq id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="FAQ deleted Successfully",
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
    public function destroy(Faq $faq)
    {
        try
        {
            $faq->delete();
            return $this->sendResponse([], trans('faq.FAQ deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }

    }

    /**
     * @OA\Post(
     *      path="/api/faq/bulk-delete",
     *      operationId="deleteBulkFaq",
     *      tags={"FAQ"},
     *      summary="Delete existing Bulk FAQ",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="id", type="integer",description="ID"),    *
     *       ),
     *        ),
     *    ),
     *       @OA\Response(
     *          response=200,
     *          description="FAQ deleted Successfully",
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
    public function bulkDelete(Request $request )
    {
        try
        {
            $ids = explode(",", $request->id);
            $orgIds = array_intersect(Faq::pluck('id')->toArray(), $ids);
            if (Faq::destroy($orgIds)) {
                return $this->sendResponse([], trans('faq.FAQ deleted Successfully'));
            }

            return $this->sendResponse([], 'Faq is not exists.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

        /**
        * @OA\Get(
        * path="/api/faq/user/{role_type}",
        * operationId="faqActive",
        * tags={"FAQ"},
        * summary="Get list of active Faq as per role type",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of active Faq as per role type",
        *      @OA\Parameter(
        *          name="role_type",
        *          description="role type",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="string"
        *          )
        *      ),
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
        public function activeFaq($roleType = 'all')
        {
            if($roleType !== 'all'){
                return $this->sendResponse(FaqCollection::collection(Faq::with('category')->whereStatus(1)->whereRoleType($roleType)->get()), 'Successfully.');

            }else{
               return $this->sendResponse(FaqCollection::collection(Faq::with('category')->whereStatus(1)->get()), 'Successfully.');
            }
        }

}
