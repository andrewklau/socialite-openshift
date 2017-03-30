<?php

namespace Andrewklau\Socialite\OpenShift;

use GuzzleHttp\Client;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'OPENSHIFT';

    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * {@inheritdoc}
     */
    protected $scopes = [];

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client([
                'verify'   => config('services.openshift.ca') ?: '',
            ]);
        }
        return $this->httpClient;
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('services.openshift.url').'oauth/authorize', $state);
    }

     /**
      * Get the token URL for the provider.
      *
      * @return string
      */
     protected function getTokenUrl()
     {
         return config('services.openshift.url').'oauth/token';
     }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $url = config('services.openshift.url').'oapi/v1/users/~';

        $response = $this->getHttpClient()->get($url, [
          'verify'   => config('services.openshift.ca') ?: '',
          'headers'  => [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.$token,
          ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return \Laravel\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['metadata']['name'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
