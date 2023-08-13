<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Models\Tickets\TicketCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Tickets\StoreTicketCategoryRequest;
use App\Http\Requests\Tickets\UpdateTicketCategoryRequest;
use App\Http\Resources\Tickets\TicketCategoryResource;
use App\Http\Resources\Tickets\TicketCategoryCollection;

class TicketCategoryController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/tickets/ticket-category",
        * operationId="Ticket Category",
        * tags={"Ticket"},
        * summary="Get list of Ticket Category",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Ticket Category",
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
       return $this->sendResponse(TicketCategoryCollection::collection(TicketCategory::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/tickets/ticket-category",
     * operationId="Ticket CategoryStore",
     * tags={"Ticket"},
     * summary="Store Ticket Category",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns Ticket Category data",
     * @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text", description="Name"),
     *               @OA\Property(property="color", type="integer" ,description="Color"),
     *               @OA\Property(property="is_default", type="integer",description="Is Default"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Ticket Category created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Ticket Category created Successfully",
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
    public function store(StoreTicketCategoryRequest $request, TicketCategory $ticketCategory)
    {
          try
        {
            $resource = $ticketCategory->create($request->all());
            return $this->sendResponse(new TicketCategoryResource($resource), trans('ticket.Ticket Category created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/tickets/ticket-category/{id}",
        * operationId="Ticket CategoryGet",
        * tags={"Ticket"},
        * summary="Get Ticket Category information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Ticket Category information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Ticket Category id",
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

            $ticketCategory = TicketCategory::find($id);
            return $this->sendResponse(new TicketCategoryResource($ticketCategory), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/tickets/ticket-category/{id}",
     * operationId="Ticket CategoryUdpate",
     * tags={"Ticket"},
     * summary="Update existing Ticket Category",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns updated Ticket Category data",
     *  @OA\Parameter(
    *          name="id",
    *          description="id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text", description="Name"),
     *               @OA\Property(property="color", type="integer" ,description="Color"),
     *               @OA\Property(property="is_default", type="integer",description="Is Default"),
     *            ),
     *        ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Ticket Category updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Ticket Category updated Successfully",
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
    public function update(UpdateTicketCategoryRequest $request, $id)
    {
        try
        {   $ticketCategory = TicketCategory::findOrFail($id);
            $ticketCategory->update($request->all());
            return $this->sendResponse(new TicketCategoryResource($ticketCategory), trans('ticket.Ticket Category updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/tickets/ticket-category/{id}",
     *      operationId="deleteTicket Category",
     *      tags={"Ticket"},
     *      summary="Delete existing Ticket Category",
    * security={
    *   {"passport": {}}
    * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Ticket Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Ticket Category deleted Successfully",
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
            TicketCategory::find($id)->delete();
            return $this->sendResponse([], trans('ticket.Ticket Category deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
