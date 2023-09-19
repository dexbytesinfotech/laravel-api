## laravel API

## Lib
- Laravel 9.x
- Twillo (SMS)
- Firebase (Push Notifications)

# Installation
1.  Run git clone https://github.com/dexbytesinfotech/laravel-api.git laravel-api
2.  Create a MySQL database for the project - `laravel-example`
3.  From the projects root run `cp .env.example .env`
4.  Configure your `.env` file   
5.  From the projects root folder run `composer update`
6.  From the projects root folder run `php artisan key:generate`
7.  From the projects root folder run `php artisan migrate:fresh --seed`
8.  From the projects root folder run `composer dump-autoload`
9.  From the projects root folder run `php artisan storage:link`
10. From the projects root folder run `php artisan l5-swagger:generate` (https://github.com/DarkaOnLine/L5-Swagger)
11. From the projects root folder run (local) `php artisan schedule:work` for server use supervisor
12. From the projects root folder run (local) `php artisan schedule:work` for server use scheduling * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1


# Admin Panel 
1. Project https://github.com/dexbytesinfotech/livewire should be after installed API code in your server or machine

### Storage folder Ownership and Permission
1. Check the permissions on the storage directory: `chmod -R 775 storage`    
1. Check the ownership of the storage directory: : `chown -R www-data:www-data storage`

### Seeds
##### Seeded Roles
  * Unverified
  * User
  * Admin
  * Manager

##### Seeded Users

|Email|Password|Access|
|:------------|:------------|:------------|
|admin@admin.com|admin123|Admin Access|

### API keys

-   [Google Maps API v3 Key](https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key)

-   [Firebase Server Key] (https://firebase.google.com/)

-   [Twilio SMS API Key](https://www.twilio.com/blog/create-sms-portal-laravel-php-twilio)

-   [Unifonic SMS API Key](https://docs.unifonic.com/docs/getting-sms-application-1)

## Remove public from url
```bash
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```
## Cron Jobs
1. Send scheduled push notification `Push\NotificationController@sendScheduledPushNotification`
 
 