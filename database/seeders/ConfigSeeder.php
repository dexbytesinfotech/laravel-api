<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Config\SystemConfig;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $default = $this->_defaultSettings();

        foreach ($default as $values) {
            SystemConfig::firstOrCreate(
                [
                    'code'      => $values['code']
                ],
                [
                    'label'     => $values['label'],
                    'value'     => $values['value']
                ]
            );
        }
    }

    /*
     * @return Array
     */
    public function _defaultSettings()
    {

        return [
            ['code' => 'app_name', 'label'  => 'App Name', 'value'  => config('app.name')],
            ['code' => 'subtitle', 'label'  => 'App Subtitle', 'value'  => config('app.subtitle')],
            ['code' => 'app_url', 'label'  => 'App URL', 'value'  => config('app.web_url')],
            ['code' => 'app_api_url', 'label'  => 'App API URL', 'value'  => config('app.url')],
            ['code' => 'timezone', 'label'  => 'Timezone', 'value'  => config('app.timezone')],
            ['code' => 'default_locale', 'label'  => 'Default Locale', 'value'  => config('app.locale')],
            ['code' => 'environment', 'label'  => 'Environment', 'value'  => config('app.env')],
            ['code' => 'debug', 'label'  => 'Debug', 'value'  => config('app.debug')],
            ['code' => 'app_logo', 'label'  => 'App Logo', 'value'  => config('general.app_logo')],
            ['code' => 'app_favicon_logo', 'label'  => 'Favicon Logo', 'value'  => config('general.app_favicon_logo')],
            ['code' => 'app_logo_transparent', 'label'  => 'App Logo Transparent', 'value'  => ''],
            ['code' => 'app_service_type', 'label'  => 'App Service Type', 'value'  => 'food'],
            ['code' => 'enable_push_notifications', 'label'  => 'Enable Push Notification', 'value'  => 1],
            ['code' => 'enable_email_notifications', 'label'  => 'Enable Email Notification', 'value'  => 1],
            ['code' => 'currency_position', 'label'  => 'Currency Position', 'value'  => 'left_with_space'],
            ['code' => 'decimal_separator', 'label'  => 'Decimal Separator', 'value'  => ','],
            ['code' => 'thousand_separator', 'label'  => 'Thousand Separator', 'value'  => '.'],
            ['code' => 'number_of_decimals', 'label'  => 'Number Of Decimals', 'value'  => 2],
            ['code' => 'time_format', 'label'  => 'Time Format', 'value'  => 'g:i A'],
            ['code' => 'date_format', 'label'  => 'Date Format', 'value'  => 'd/m/Y'],
            ['code' => 'currency', 'label'  => 'Currency', 'value'  => config('general.currency')],
            ['code' => 'currency_symbol', 'label'  => 'Currency Symbol', 'value'  => config('general.currency_symbol')],
            ['code' => 'apple_store_app_url', 'label'  => 'Apple Store App URL', 'value'  => ''],
            ['code' => 'play_store_app_url', 'label'  => 'Play Store App URL', 'value'  => ''],
            ['code' => 'support_number', 'label'  => 'Support Number', 'value'  => '910000000000'],
            ['code' => 'week_start', 'label'  => 'Week start', 'value'  => 'monday'],
            ['code' => 'percentage_restaurant', 'label'  => 'Percentage of the restaurant', 'value'  => 95],
            ['code' => 'play_store_version', 'label'  => 'Play store version', 'value'  => 1.0],
            ['code' => 'apple_store_version', 'label'  => 'Apple store version', 'value'  => 1.0],
            ['code' => 'pagination_per_page', 'label'  => 'Pagination Qty', 'value'  => 50],
            ['code' => 'filesystem_disk', 'label'  => 'Filesystem - local, public, s3', 'value'  => "s3"],
            ['code' => 'store_logo', 'label'  => 'Store default logo', 'value'  => "dummy/store.png"],
            ['code' => 'store_background_image', 'label'  => 'Store background default image', 'value'  => "dummy/store.png"],
            ['code' => 'customer_image', 'label'  => 'Customer default image', 'value'  => "dummy/user.png"],
            ['code' => 'provider_image', 'label'  => 'Provider default image', 'value'  => "dummy/user.png"],
            ['code' => 'default_latitude', 'label'  => 'Default latitude', 'value'  => 22.758940],
            ['code' => 'default_longitude', 'label'  => 'Default longitude', 'value'  => 75.891418],
            ['code' => 'support_email', 'label'  => 'Support Email', 'value'  => 'example@example.com'],
            ['code' => 'enable_slack_log_notifications', 'label'  => 'Enable Slack Log Notification', 'value'  => 1],
            ['code' => 'notification_email', 'label'  => 'notification mail id (For testing only)', 'value'  => 'example.mail@gmail.com'],
            ['code' => 'enable_mail_notifications', 'label'  => 'Enable Mail Notification', 'value'  => 0],
            ['code' => 'default_lang', 'label'  => 'Default language code', 'value'  => 'en'],
            ['code' => 'shared_url', 'label'  => 'Store Shared Url', 'value'  => ''],
            ['code' => 'enable_sms_notifications', 'label'  => 'SMS Notification Enable', 'value'  => config('general.enable_sms_notifications')],
            ['code' => 'price_with_tax', 'label'  => 'Price With Tax', 'value'  => true],
            ['code' => 'deep_link_url', 'label'  => 'Deep link url', 'value'  =>  parse_url(config('app.url'), PHP_URL_HOST)],
            ['code' => 'website_title', 'label'  => 'Display app name', 'value'  =>  'logo'], // logo/name/name_and_logo
            ];

    }


}
