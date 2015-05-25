# gaia-users
User management module for the Gaia CMS

The package will publish the following:
* views
* model repositories (PSR4 autoloaded and binded)
* seeds (in database folder)
* models

The controller, (validation) request and the routes will be autoloaded from the package itself.

#### Installation
Run the following command in your terminal 
```
composer require eandraos/gaia-users
```

Then register this service provider with Laravel in config/app.php
```
Gaia\Users\GaiaUsersServiceProvider
```

Publish the different files
```
php artisan vendor:publish
```

Update the entrust.php file in config/ directory
```
'role' => 'App\Models\Role',
...
'permission' => 'App\Models\Permission',
```

add the following method to the User model
```
public function getRole()
{
	if($this->roles)
		return $this->roles[0];

	return null;
}
```

#### Usage
Add PSR-4 autoload in the composer.json 
```
"Gaia\\": "app/Gaia"
```

Dump the class autoload in the terminal 
```
composer dump-autoload -o
```

Create the seeds
```
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=PermissionTableSeeder
```
