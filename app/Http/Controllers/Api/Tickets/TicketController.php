<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Models\Tickets\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Tickets\StoreTicketRequest;
use App\Http\Requests\Tickets\UpdateTicketRequest;
use App\Http\Resources\Tickets\TicketResource;
use App\Http\Resources\Tickets\TicketCollection;

class TicketController extends BaseController
{


    public function __construct()
    {
        //
    }

     /**
        * @OA\Get(
        * path="/api/ticket",
        * operationId="Ticket",
        * tags={"Ticket"},
        * summary="Get list of Ticket",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Ticket",
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
       return $this->sendResponse(TicketCollection::collection(Ticket::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/ticket",
     * operationId="TicketStore",
     * tags={"Ticket"},
     * summary="Store Ticket",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns Ticket data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"title", "user_id", "category_id"},
     *               @OA\Property(property="title", type="text", description="Title"),
     *               @OA\Property(property="content", type="text" ,description="Content"),
     *               @OA\Property(property="status", type="text",description="Status"),
     *               @OA\Property(property="category_id", type="integer",description="select Category"),
     *               @OA\Property(property="user_id", type="integer",description="logged in user id"),
     *               @OA\Property(property="assigned_to_user_id", type="integer",description="assing a tickets user id"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Ticket created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Ticket created Successfully",
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
    public function store(StoreTicketRequest $request, Ticket $ticket)
    {
        try {
            $resource = $ticket->create($request->all());
            return $this->sendResponse(new TicketResource($resource), trans('ticket.Ticket created Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
        * @OA\Get(
        * path="/api/ticket/{id}",
        * operationId="TicketGet",
        * tags={"Ticket"},
        * summary="Get Ticket information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Ticket information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Ticket id",
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

            $ticket = Ticket::find($id);
            return $this->sendResponse(new TicketResource($ticket), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

   /**
     * @OA\Put(
     * path="/api/ticket/{id}",
     * operationId="TicketUdpate",
     * tags={"Ticket"},
     * summary="Update existing Ticket",
    * security={
    *   {"passport": {}}
    * },
     * description="Returns updated Ticket data",
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
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"title", "user_id", "category_id"},
     *               @OA\Property(property="title", type="text", description="Title"),
     *               @OA\Property(property="content", type="text" ,description="Content"),
     *               @OA\Property(property="status", type="text",description="Status"),
     *               @OA\Property(property="category_id", type="integer",description="select Category"),
     *               @OA\Property(property="user_id", type="integer",description="logged in user id"),
     *               @OA\Property(property="assigned_to_user_id", type="integer",description="assing a tickets user id"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Ticket updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Ticket updated Successfully",
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
    public function update(UpdateTicketRequest $request, $id)
    {
        try
        {   $ticket = Ticket::findOrFail($id);
            $ticket->update($request->all());
            return $this->sendResponse(new TicketResource($ticket), trans('ticket.Ticket updated Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/ticket/{id}",
     *      operationId="deleteTicket",
     *      tags={"Ticket"},
     *      summary="Delete existing Ticket",
    *       security={
    *            {"passport": {}}
    *       },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Ticket id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Ticket deleted Successfully",
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
            Ticket::find($id)->delete();
            return $this->sendResponse([], trans('ticket.Ticket deleted Successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
