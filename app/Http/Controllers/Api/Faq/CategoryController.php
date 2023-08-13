<?php

namespace App\Http\Controllers\Api\Faq;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Faq\FaqCategory;
use App\Http\Resources\Faq\FaqCategoryCollection;
use App\Http\Resources\Faq\FaqCategoryResource;
use App\Http\Requests\Faq\FaqCategoryStoreRequest;
use App\Http\Requests\Faq\FaqCategoryUpdateRequest;

class CategoryController extends BaseController
{
    public function __construct()
    {
        //
    }

       /**
        * @OA\Get(
        * path="/api/faq/category",
        * operationId="faqCategory",
        * tags={"FAQ Category"},
        * summary="Get list of Faq Category",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Faq Category",
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
        return $this->sendResponse(FaqCategoryCollection::collection(FaqCategory::all()), 'Successfully.');
    }

       /**
     * @OA\POST(
     * path="/api/faq/category",
     * operationId="faqCreateCategory",
     * tags={"FAQ Category"},
     * summary="Store Faq Category",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Faq Category data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="status", type="integer" ,description="Status (<small>char limit(20)</small>)"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Category created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Category created Successfully",
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
    public function store(FaqCategoryStoreRequest $request, FaqCategory $faqCategory)
    {
        try
        {
            $faqCategoryResource = $faqCategory->create($request->all());
            return $this->sendResponse(new FaqCategoryResource($faqCategoryResource), trans('faq.Category created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

     /**
        * @OA\Get(
        * path="/api/faq/category/{id}",
        * operationId="faqGetCategory",
        * tags={"FAQ Category"},
        * summary="Get Faq Category information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Faq Category information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Category id",
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

            $faqCategory = FaqCategory::find($id);
            return $this->sendResponse(new FaqCategoryResource($faqCategory), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/faq/category/{id}",
     * operationId="faqUpdateCategory",
     * tags={"FAQ Category"},
     * summary="Store Faq Category",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Faq Category data",
     *     @OA\Parameter(
    *          name="id",
    *          description="Category id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *         ),
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *                @OA\Property(property="name", type="text",description="Name (<small>char limit(100)</small>)"),
     *               @OA\Property(property="status", type="integer" ,description="Status (<small>char limit(20)</small>)"),
     *            ),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Category updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Category updated Successfully",
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
    public function update(FaqCategoryUpdateRequest $request, $id)
    {
        try
        {   $faqCategory = FaqCategory::findOrFail($id);
            $faqCategory->update($request->all());
            return $this->sendResponse(new FaqCategoryResource($faqCategory), trans('faq.Category updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }


   /**
     * @OA\Delete(
     *      path="/api/faq/category/{id}",
     *      operationId="deletefaqCategory",
     *      tags={"FAQ Category"},
     *      summary="Delete existing Category",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Category deleted Successfully",
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
            FaqCategory::find($id)->delete();
            return $this->sendResponse([], trans('faq.Category deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }

    }
}
