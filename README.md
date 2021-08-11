
# Laravel Feature Toggle

[![Latest Version on Packagist](https://img.shields.io/packagist/v/charlgottschalk/feature-toggle.svg?style=flat-square)](https://packagist.org/packages/charlgottschalk/feature-toggle)
[![Total Downloads](https://img.shields.io/packagist/dt/charlgottschalk/feature-toggle.svg?style=flat-square)](https://packagist.org/packages/charlgottschalk/feature-toggle)

Feature toggling is a coding strategy used along with source control to make it easier to continuously integrate and deploy. 
The idea of the toggles essentially prevents sections of code from executing if a feature is disabled.

---

_* This is a work in progress, but is stable and working_

LFT provides a simple package for implementing feature toggles allowing you to switch features on and off using a simple GUI or with Artisan commands.

Oh, and it supports user roles, your user roles.

## Installation

---

#### 1. Install the package using composer:
```
$ composer require charlgottschalk\feature-toggle
```

The package should be auto-discovered by Laravel, but if it's not, simply add the service provider to your `config/app.php` providers array:
```php
'providers' => [
    //...
    CharlGottschalk\FeatureToggle\FeatureToggleServiceProvider::class,
],
```

#### 2. Publish the assets:

**Config:**
```
$ php artisan vendor:publish --provider="CharlGottschalk\FeatureToggle\FeatureToggleServiceProvider" --tag="config"
```

**Assets:**
```
$ php artisan vendor:publish --provider="CharlGottschalk\FeatureToggle\FeatureToggleServiceProvider" --tag="assets"
```

**Views:**
_This is not necessary unless you'd like to make changes to the GUI_
```
$ php artisan vendor:publish --provider="CharlGottschalk\FeatureToggle\FeatureToggleServiceProvider" --tag="views"
```

#### 3. Migrate
Run `$ php artisan migrate` to create the feature toggle tables.

## Usage

---

LFT provides a few easy to use blade directives, helper functions and middleware to determine if features are enabled.

In addition, there is a GUI to manage your features, accessible at `http://domain.local/features` - See [prefix](#prefix) 

#### Blade

To check if a feature is enabled - no roles checked
```angular2html
@enabled('feature_name')
    <p>This feature is enabled</p>
@endenabled
```

To check if a feature is enabled, including if the authenticated user has permission via a role 
```angular2html
@enabledFor('feature_name')
    <p>This feature is enabled</p>
@endenabledFor
```
LFT will attempt to retrieve the user's role via the property and roles configuration (see config) when determining if a user has permission to access a feature.

#### Facade

LFT provides a facade to easily check feature toggles in controllers etc.
```php
use CharlGottschalk\FeatureToggle\Facades\Feature;

if (Feature::enabled('feature_name')) {
    // Feature is enabled
}

if (Feature::enabledFor('feature_name')) {
    // Feature is enabled and user has permission
}
```

#### Helpers

LFT provides helper functions for checking feature toggles.
```php
if (feature_enabled('feature_name')) {
    // Feature is enabled
}

if (enabled_for('feature_name')) {
    // Feature is enabled and user has permission
}
```

#### Middleware

LFT provides middleware for disabling routes if a feature is disabled or the user does not have permission.

Add the middleware to your `app\Http\Kernel.php` `$routeMiddleware` array.

```php
protected $routeMiddleware = [
        // Other middleware
        'feature' => \CharlGottschalk\FeatureToggle\Http\Middleware\CheckFeature::class,
        'feature.role' => \CharlGottschalk\FeatureToggle\Http\Middleware\CheckFeatureRole::class,
    ];
```

Add the middleware to your required routes.
```php
Route::get('/some-url', [App\Http\Controllers\SomeController::class, 'index'])->middleware('feature:can_see_feature');
Route::get('/another-url', [App\Http\Controllers\AnotherRoleController::class, 'index'])->middleware('feature.role:can_see_feature_role');
```

The middleware will `abort(404)` if the given feature is disabled or the user does not have permission.

## Config

---

#### `'on' => true`:
Switch LFT off (`false`) or on (`true`) - When switched off, LFT will not retrieve feature status from the database.

#### `'all_on' => true`: 
If LFT is switched off, this determines if the checks return 'enabled' (`'all_on' => true`) or 'disabled' (`'all_on' => false`) for all features.

#### `'connection' => env('DB_CONNECTION', 'mysql')`:
Specify the database connection to use for LFT migrations and models - Useful, if you prefer your feature toggles to exist outside your application database.

#### `'roles.property' => 'role'`:
LFT uses this to determine which property to use on the user model to retrieve the user's role.

#### `'roles.model' => \App\Models\Role::class`:
Specify which model represents roles in your application.

#### `'roles.column' => 'name'`:
Specify which column on the role model/table represents the name of a role.

#### `'auth.role' => 'developer'`:
Specify which role a user should have to access the feature toggle GUI.

#### `'route.middleware' => ['auth']`:
Specify which middleware should be used for feature toggle GUI routes.

#### <a name="prefix"></a>`'route.prefix' => 'features'`:
Specify the prefix to use for feature toggle GUI routes - In this case, the GUI will be accessible at `http://domain.local/features`.

## ToDo:

---

- [ ] Validation Rules
- [ ] Scheduling Checks
- [ ] API Support
- [ ] Lumen Support

## License

---

The MIT License (MIT). Please see [License File](LICENSE) for more information.
