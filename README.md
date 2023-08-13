## Moadcar API

## Lib
- Laravel 9.x
- Twillo 
- Firebase

# Installation
1. From the projects root folder run `composer update`
2. From the projects root folder run `php artisan key:generate`
3. From the projects root folder run `php artisan migrate:fresh --seed`
4. From the projects root folder run `composer dump-autoload`
5. From the projects root folder run `php artisan storage:link`
6. From the projects root folder run `php artisan l5-swagger:generate`
7. From the projects root folder run (local) `php artisan schedule:work` for server use supervisor
8. From the projects root folder run (local) `php artisan schedule:work` for server use scheduling * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1



### Folder Ownership and Permission
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


## Remove public from url
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

## Cron Jobs
1. Send scheduled push notification `Push\NotificationController@sendScheduledPushNotification`

## S3 public Dummy images 
{
	"Version": "2008-10-17",
	"Statement": [
		{
			"Sid": "AllowPublicRead",
			"Effect": "Allow",
			"Principal": {
				"AWS": "*"
			},
			"Action": "s3:GetObject",
			"Resource": "arn:aws:s3:::dingyy-app/dummy/*"
		}
	]
}


## supervisorctl Jobs
sudo supervisorctl stop all
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
ps aux | grep artisan