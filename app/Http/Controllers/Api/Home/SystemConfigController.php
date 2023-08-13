<?php

namespace App\Http\Controllers\Api\Home;

use Carbon\Carbon;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Events\InstantMailNotification;
use App\Jobs\TestingJob;
use Illuminate\Support\Facades\Artisan;
use App\Models\Worlds\Country;
use App\Constants\PaymentMethodsArray;
use Illuminate\Support\Arr;
use App\Constants\DateTimeFormat;

class SystemConfigController extends BaseController
{

    /**
     *      @OA\GET(
     *      path="/api/system/settings",
     *      operationId="SystemSettings",
     *      tags={"System Settings"},
     *      summary="Get System default settings",
     *      description="Returns all data of System default settings",
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
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
     *  )
     */
    public function index()
    {
        try {

            $date_key = config('app_settings.date_format.value');
            $date_format = Arr::get(DateTimeFormat::DateTimeFormat,$date_key);

            $time_key = config('app_settings.time_format.value');
            $time_format = Arr::get(DateTimeFormat::DateTimeFormat,$time_key);

            $label = array();
            foreach (PaymentMethodsArray::MethodArray as $key => $value) {
                $value['name'] = __('payment_methods.'.$value['name']);
                array_push($label,$value);
            }

            $data = [
                "app_name"  => (string) config('app_settings.app_name.value'),
                "app_url"  => (string) config('app_settings.app_url.value'),
                "debug"  => (string) config('app_settings.debug.value'),
                "app_logo"  => (string) config('app_settings.app_logo.value'),
                "app_favicon_logo"  => (string) config('app_settings.app_favicon_logo.value'),
                "app_logo_transparent"  => (string) config('app_settings.app_logo_transparent.value'),
                "apple_store_app_url"  => (string) config('app_settings.apple_store_app_url.value'),
                "play_store_app_url"  => (string) config('app_settings.play_store_app_url.value'),
                "enable_push_notifications"  => (boolean) config('app_settings.enable_push_notifications.value'),
                "enable_email_notifications"  => (boolean) config('app_settings.enable_email_notifications.value'),
                "currency_position"  => (string) config('app_settings.currency_position.value'),
                "thousand_separator"  => (string) config('app_settings.thousand_separator.value'),
                "decimal_separator"  => (string) config('app_settings.decimal_separator.value'),
                "number_of_decimals"  => (integer) config('app_settings.number_of_decimals.value'),
                "time_format"  => (string) $time_format,
                "date_format"  => (string) $date_format,
                "currency"  => (string) config('app_settings.currency.value'),
                "support_number"  => (integer) config('app_settings.support_number.value'),
                "week_start"  => (string) config('app_settings.week_start.value'),
                "order_at_one_time_to_driver"  => (integer) config('app_settings.order_at_one_time_to_driver.value'),
                "percentage_restaurant"  => (integer) config('app_settings.percentage_restaurant.value'),
                "play_store_version"  => (integer) config('app_settings.play_store_version.value'),
                "apple_store_version"  => (integer) config('app_settings.apple_store_version.value'),
                "currency_symbol"  => (string) config('app_settings.currency_symbol.value'),
                "shared_url"  => (string) config('app_settings.shared_url.value'),
                'price_with_tax' => (boolean) config('app_settings.price_with_tax.value'),
                "country_iso_code_list" =>  Country::where('status',true)->whereNot('country_ios_code',null)->orderBy('name', 'ASC')->pluck('country_ios_code'),
                "payment_method" =>  $label,
                'deep_link_url' => (string) config('app_settings.deep_link_url.value')
            ];

            return $this->sendResponse($data, 'No results found');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => Response::HTTP_NOT_FOUND]);
        }
    }


    /**
     *      @OA\GET(
     *      path="/api/test/job",
     *      operationId="JobTest",
     *      tags={"System Settings"},
     *      summary="Get System default settings",
     *      description="Returns all data of System default settings",
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
     *  )
     */

    public function testJob()
    {
            TestingJob::dispatch([
                "action_type" => "testing"
            ])->delay(Carbon::now()->addSeconds(5));

            Log::info('TestingJob.', ['delay' => Carbon::now()->addSeconds(5),  'timestamp' => Carbon::now()->toDateTimeString()]);

            return $this->sendResponse([], 'No results found');
    }

    /**
     *      @OA\GET(
     *      path="/api/test/mail",
     *      operationId="MailTest",
     *      tags={"System Settings"},
     *      summary="testing mailgum mail",
     *      description="Returns all data of System default settings",
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
     *  )
     */

    public function testMail()
    {

        // return User::pluck('email','name');
        $code = "forget_password";
        $args = [
            'name' => "Arjun",
        ];

        event(new InstantMailNotification(52, [
            "code" =>  'forget_password',
            "args" => [
                'name' => "Arjun",
            ]
        ]));

        // $mail =  Mail::to(config('app_settings.notification_email.value'))->send(new SendMailNotification($code,$args));
        // return $this->sendResponse($mail, 'No results found');
    }



    /**
     *      @OA\GET(
     *      path="/api/system/cache/{command}",
     *      operationId="CommandCache",
     *      tags={"System Settings"},
     *      summary="Clear Cache and cookie",
     *      description="Returns all data of Status",
        *  @OA\Parameter(
        *          name="command",
        *          description="command (cache:clear,config:clear,view:clear,l5-swagger:generate) ",
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
     *  )
     */

    public function command($command)
    {
        try{
            if (in_array($command, ['cache:clear','config:clear','view:clear', 'l5-swagger:generate'])){

                Artisan::call($command);
                return $this->sendResponse([], 'Successfully');
            }

            return $this->sendError(['Command Not Found']);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

      /**
     *      @OA\POST(
     *      path="/api/error/report",
     *      operationId="ErrorReport",
     *      tags={"System Settings"},
     *      summary="Store error report",
     *      description="Returns all data of Status",
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"error"},
     *                  @OA\Property(property="error", type="string", example="something went wrong", description="Error json"),
     *              ),
     *          ),
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
     *  )
     */

    public function ErrorReport(Request $request)
    {
        Log::info($request);
        return $this->sendResponse([], 'Successfully');
    }
}
