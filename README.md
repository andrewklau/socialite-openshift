# OpenShift OAuth2 Provider for Laravel Socialite

## Documentation

This package makes use of the `SocialiteProviders` package located [here](http://socialiteproviders.github.io/).  

### Install the package

```sh
composer require andrewklau/socialite-openshift
```

### Install the Service Provider

* Remove `Laravel\Socialite\SocialiteServiceProvider` from your providers[] array in config\app.php if you have added it already.

* Add `\SocialiteProviders\Manager\ServiceProvider::class` to your providers[] array in config\app.php.


### Install the event listener

* Add `SocialiteProviders\Manager\SocialiteWasCalled` event to your listen[] array in `<app_name>/Providers/EventServiceProvider`.


* The listener that you add for this provider is `'Andrewklau\Socialite\OpenShift\OpenShiftkExtendSocialite@handle',`.

For example:

```php
/**
 * The event handler mappings for the application.
 *
 * @var array
 */
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // add your listeners (aka providers) here
        'Andrewklau\Socialite\OpenShift\OpenShiftExtendSocialite@handle',
    ],
];
```

### Environment variables

If you add environment values to your `.env` as exactly shown below, you do not need to add an entry to the services array.

#### Append to .env

```
// other values above
OPENSHIFT_URL=https://api.xyz.com
OPENSHIFT_OAUTH_CLIENT_ID=yourkeyfortheservice
OPENSHIFT_OAUTH_CLIENT_SECRET=yoursecretfortheservice
```

#### Append to config/services.php

You do not need to add this if you add the values to the `.env` exactly as shown above. The values below are provided as a convenience in the case that a developer is not able to use the .env method

```php
'openshift' => [
    'client_id'     => env('OPENSHIFT_OAUTH_CLIENT_ID'),
    'client_secret' => env('OPENSHIFT_OAUTH_CLIENT_SECRET'),
    'url'           => env('OPENSHIFT_URL'),
    'redirect'      => env('APP_URL').'/login/callback',
],
```

## Usage

Redirect to OpenShift with the scopes you want to access:

```php
return Socialite::with('OpenShift')->scopes()->redirect();
```

## License

MIT
