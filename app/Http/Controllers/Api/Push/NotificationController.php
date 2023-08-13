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
use App\Http\Requests\Push\AddNotificationRequest;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Log;

class NotificationController extends BaseController
{


    /**
     * @OA\Post(
     * path="/api/notification/send-all/",
     * operationId="sendNotification",
     * tags={"Notification"},
     * security={{"passport":{}}},
     * summary="Send Notification to all user",
     * security={
     *   {"passport": {}}
     * },
     * description="Send Notification to all user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"title", "body"},
     *               @OA\Property(property="title", type="string", description="title"),
     *               @OA\Property(property="body", type="string", description="body"),
     *               @OA\Property(property="user_id", type="string", description="user_id")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Notification send successfully",
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
    public function sendToAllNotification(Request $request)
    {
        try {
            $userId = $request->get('user_id');
            $pushDevice = new PushDevice;
            if ($userId) {
                $pushDevice = $pushDevice->where('user_id', $userId);
            }
            $firebaseToken  = $pushDevice->pluck('device_token_id')->toArray();
            if ($firebaseToken) {
                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => $request->get('title'),
                        "body" => $request->get('body'),
                    ]
                ];

                $output = (new FCMService)->send($data);

                return $this->sendResponse($output, trans('push.Notification send successfully'));
            }

            return $this->sendError(trans('push.No device found'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\POST(
     *  path="/api/notification/add-notification",
     *  operationId="addNotification",
     *  tags={"Notification"},
     *  summary="Add a Push Notification to send user(s)",
     *  security={
     *      {"passport": {}}
     *  },
     *  description="Add a Notification to be send",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"target_devices", "title", "text", "send_to","app_name","send_at"},
     *               @OA\Property(property="target_devices", type="text",description="Target Devices (<small>Like(all,ios,android)</small>)"),
     *               @OA\Property(property="title", type="text", description="Title"),
     *               @OA\Property(property="text", type="text", description="Text"),
     *               @OA\Property(property="with_image", type="integer",description="With Image"),
     *               @OA\Property(property="custom_image", type="text",description="Custom Image"),
     *               @OA\Property(property="action_value", type="string",description="Action Value (type array) "),
     *               @OA\Property(property="send_to", type="enum",description="Send to all or Specific users (all,specific_users)"),
     *               @OA\Property(property="user_ids", type="string",description="Comma separated user ids (1,2,3,4)"),
     *               @OA\Property(property="send_at", type="datetime",description="Send at Specific time (<small> y-m-d,h-m-s</small>)"),
     *               @OA\Property(property="send_until", type="datetime",description="Send until Specific time (<small> y-m-d,h-m-s</small>)"),
     *               @OA\Property(property="app_name", type="enum",description="Target App('all','provider','customer')"),
     *               @OA\Property(property="is_silent", type="integer",description="Notification sound"),
     *               @OA\Property(property="latitude", type="decimal",description="Latitude"),
     *               @OA\Property(property="longitude", type="decimal",description="Longitude"),
     *               @OA\Property(property="radius", type="integer", description="Radius in KM " ),
     *               @OA\Property(property="should_visible", type="integer", description="Message shoul display to user or not (0,1)" ),
     *
     *            ),
     *        ),
     *      ),
     *      @OA\Response(response=201, description="Store created Successfully", @OA\JsonContent()),
     *      @OA\Response(response=200, description="Store created Successfully",@OA\JsonContent()),
     *      @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function addNotificationMessage(AddNotificationRequest $request)
    {

        try {
            $request->merge(['status' => 'queue']);
            $pushMessage = PushMessage::create($request->all());

            if ($request->get('send_to') == 'specific_users' && $request->get('user_ids') != null  && $request->get('user_ids') != '') {

                $userIdArray = array_filter(explode(',', $request->get('user_ids')));
                $userMessage = [];

                foreach ($userIdArray as $userId) {
                    array_push($userMessage, [
                        'message_id' => $pushMessage['id'],
                        'user_id' => $userId
                    ]);
                }

                PushUserMessage::insert($userMessage);
            }
            return $this->sendResponse([], trans('push.Notification send successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }

    public function sendScheduledPushNotification()
    {

        $pushMessages = PushMessage::where('status', 'queue')
            ->where('send_at', '<=', now())
            ->where(
                function ($query) {
                    $query->where('send_until', '>=', now())
                        ->orWhereNull('send_until');
                }
            )
            ->get()->toArray();

        if(count($pushMessages) > 0) {
            Log::info('Schedule Push Notification Reminder cron successfully run.', [ 'timestamp' => Carbon::now()->toDateTimeString()]);
            Log::channel('day')->info('Schedule Push Notification Reminder cron - ', ['pushMessagesCount' => count($pushMessages),  'timestamp' => Carbon::now()->toDateTimeString()]);
        }

        if(config('app_settings.enable_slack_log_notifications.value') && count($pushMessages) > 0) {
            Log::channel('slackNotification')->info("Cron: Push Notification - ".config('app.name'), [
                'Info' => ['total records ' => count($pushMessages) ]
            ]);
        }

        if (!empty($pushMessages)) {
            foreach ($pushMessages as $pushMessage) {
                $pushDevice = new PushDevice;
                if ($pushMessage['app_name'] != 'all') {
                    $pushDevice = $pushDevice->where('app_name', $pushMessage['app_name']);
                }
                if ($pushMessage['target_devices'] != 'all') {
                    $pushDevice = $pushDevice->where('device_type', $pushMessage['target_devices']);
                }

                if ($pushMessage['send_to'] == 'specific_users') {
                    $specificUsers = PushDeliveredMessage::where('message_id', $pushMessage['id'])->groupBy('user_id')->pluck('user_id')->toArray();
                    $pushDevice = $pushDevice->whereIn('user_id', $specificUsers);
                }


                $pushDevices  = $pushDevice->whereNotNull('device_token_id')->get()->toArray();

                $deviceInfo = [];
                $firebaseToken = [];
                foreach ($pushDevices as $pushDevice) {
                    array_push($deviceInfo, [
                        'device_id' => $pushDevice['id'],
                        'device_uid' => $pushDevice['device_uid'],
                        'device_type' => $pushDevice['device_type'],
                        'user_id' => $pushDevice['user_id'],
                        'message_id' => $pushMessage['id'],
                    ]);
                    array_push($firebaseToken, $pushDevice['device_token_id']);
                }

                if (!empty($deviceInfo)) {
                    PushDeliveredMessage::insert($deviceInfo);
                }

                if ($firebaseToken) {
                    $data = [
                        "registration_ids" => $firebaseToken,
                        "notification" => [
                            "title" => $pushMessage['title'],
                            "body" => $pushMessage['text'],
                        ]
                    ];
                    $output = (new FCMService)->send($data);

                    foreach ($deviceInfo as $index => $deliveredMessage) {
                        $status = isset($output['results'][$index]['error']) ? 'fail' : 'deliver';
                        $data = PushDeliveredMessage::updateOrCreate(
                            [
                                'device_id' => $deliveredMessage['device_id'],
                                'user_id' => $deliveredMessage['user_id'],
                                'message_id' => $deliveredMessage['message_id']
                            ],
                            [
                                'status' => $status,
                                'error_msg' => ($status == 'fail') ? json_encode($output['results'][$index]['error']) : null,
                                'delivered_at' => ($status == 'deliver') ? Carbon::now()->toDateTimeString() : null,
                            ]
                        );
                    }
                }

                PushMessage::where('id', $pushMessage['id'])->update(['status' => 'sent']);
            }
        }
    }
}
