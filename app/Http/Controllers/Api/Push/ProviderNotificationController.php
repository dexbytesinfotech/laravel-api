<?php

namespace App\Http\Controllers\Api\Push;

use Carbon\Carbon;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Push\PushDevice;
use App\Models\Push\PushMessage;
use App\Models\Push\PushUserMessage;
use App\Events\InstantPushNotification;
use App\Models\Push\PushDeliveredMessage;
use App\Http\Resources\Push\PushNotificationResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Log;


class ProviderNotificationController extends BaseController
{
    /**
     * @OA\Get(
     * path="/api/provider/notifications",
     * operationId="providerNotificationList",
     * tags={"Notification"},
     * security={{"passport":{}}},
     * summary="Provider notification list",
     * security={
     *   {"passport": {}}
     * },
     * description="Provider notification list",
     *      @OA\Response(
     *          response=201,
     *          description="Notification list",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Notification send successfully",
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
    public function notificationList(Request $request)
    {
        try {
            $notifications =  PushMessage::where('should_visible', '=', 1)
            ->whereHas('PushDeliveredMessage', function ($query) {
                $query->where('user_id', auth()->user()->id)->where('status', 'deliver');
                $query->whereHas('device', function ($query) {
                    return $query->where('app_name', '=', 'provider');
                });
            })->orderBy('created_at', 'DESC')->get();

            return $this->sendResponse(PushNotificationResource::collection($notifications), trans('push.Push Notification list'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


     /**
     * @OA\Put(
     *  path="/api/provider/notification/read-display/{message_id}",
     *  operationId="notificationProviderReadDisplay",
     *  tags={"Notification"},
     *  security={{"passport":{}}},
     *  summary="Mark Notification as read and display for In App notifications",
     *  security={
     *      {"passport": {}}
     *  },
     *  description="Mark Notification as read and display for In App notifications",
     * @OA\Parameter(
     *          name="message_id",
     *          description="Message Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Response(response=201,description="Notification send successfully", @OA\JsonContent()),
     *  @OA\Response(response=200,description="Notification send successfully",@OA\JsonContent()),
     *  @OA\Response(response=422,description="Unprocessable Entity",@OA\JsonContent()),
     *  @OA\Response(response=400, description="Bad request"),
     *  @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function markNotificationReadDisplay(Request $request, $message_id)
    {
        try {
        if(auth()->user()->id) {
            $message = PushDeliveredMessage::where('message_id', $message_id)->where('user_id',  auth()->user()->id);
            $message->update(['is_read' => 'yes', 'is_displayed' => 'yes']);

            return $this->sendResponse([], trans('push.Message status updated successfully'));
        }

        return $this->sendError(['error' => Response::HTTP_NOT_FOUND]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }



    /**
     * @OA\Put(
     *  path="/api/provider/notification/read/{messageId}",
     *  operationId="providerNotificationRead",
     *  tags={"Notification"},
     *  security={{"passport":{}}},
     *  summary="Mark Notification as read",
     *  security={
     *      {"passport": {}}
     *  },
     *  description="Mark Notification as read",
     * @OA\Parameter(
     *          name="messageId",
     *          description="Message Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Response(response=201,description="Notification send successfully", @OA\JsonContent()),
     *  @OA\Response(response=200,description="Notification send successfully",@OA\JsonContent()),
     *  @OA\Response(response=422,description="Unprocessable Entity",@OA\JsonContent()),
     *  @OA\Response(response=400, description="Bad request"),
     *  @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function markNotificationRead(Request $request, $message_id)
    {
        try {

            $message = PushDeliveredMessage::where('message_id', $message_id)->where('user_id',  auth()->user()->id);
            $message->update(['is_read' => 'yes', 'is_displayed' => 'yes']);

            return $this->sendResponse([], trans('push.Message status updated successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *  path="/api/provider/notification/display",
     *  operationId="markProviderNotificationsDiyplayed",
     *  tags={"Notification"},
     *  security={{"passport":{}}},
     *  summary="Mark Notifications as displayed",
     *  security={
     *      {"passport": {}}
     *  },
     *  description="Mark Notifications as displayed",
     *  @OA\Response(response=201,description="Notification send successfully", @OA\JsonContent()),
     *  @OA\Response(response=200,description="Notification send successfully",@OA\JsonContent()),
     *  @OA\Response(response=422,description="Unprocessable Entity",@OA\JsonContent()),
     *  @OA\Response(response=400, description="Bad request"),
     *  @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function markNotificationsDiyplayed(Request $request)
    {
        try {
            PushDeliveredMessage::whereHas('device', function ($query) {
                return $query->where('app_name', '=', 'provider');
            })->where('user_id', auth()->user()->id)->where('status', 'deliver')->update(['is_displayed' => 'yes']);

            $notifications =  PushMessage::where('should_visible', '=', 1)
            ->whereHas('PushDeliveredMessage', function ($query) {
                $query->where('user_id', auth()->user()->id)->where('status', 'deliver');
                $query->whereHas('device', function ($query) {
                    return $query->where('app_name', '=', 'provider');
                });
            })->orderBy('created_at', 'DESC')->get();

            return $this->sendResponse(PushNotificationResource::collection($notifications), trans('push.Message status updated successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *  path="/api/provider/notification/delete/{message_id}",
     *  operationId="providerNotificationDelete",
     *  tags={"Notification"},
     *  security={{"passport":{}}},
     *  summary="Delete Notification",
     *  security={
     *      {"passport": {}}
     *  },
     *  description="Delete Notification",
     * @OA\Parameter(
     *          name="message_id",
     *          description="Message Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Response(response=201,description="Notification delete successfully", @OA\JsonContent()),
     *  @OA\Response(response=200,description="Notification delete successfully",@OA\JsonContent()),
     *  @OA\Response(response=422,description="Unprocessable Entity",@OA\JsonContent()),
     *  @OA\Response(response=400, description="Bad request"),
     *  @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function deleteNotification(Request $request, $message_id)
    {
        try {

            $res = PushDeliveredMessage::where('user_id', auth()->user()->id)
                ->where('id', $message_id)->delete();
                PushMessage::where('id', $message_id)->delete();

            return $this->sendResponse(['result' => $res], ($res) ? trans('push.Notification deleted successfully') : trans('push.Delete Operation Fail'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
