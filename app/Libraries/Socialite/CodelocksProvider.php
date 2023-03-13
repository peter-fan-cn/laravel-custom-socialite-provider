<?php

namespace App\Libraries\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class CodelocksProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['*'];

    private $host = 'http://cw-id.local';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->host . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->host . '/oauth/token';
    }

    protected function getUserByToken($token)
    {
        Log::debug('token:' . $token);
        $userUrl = $this->host . '/api/user';

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'     => $user['id'],
            'name'   => Arr::get($user, 'name'),
            'email'  => Arr::get($user, 'email'),
            'avatar' => Arr::get($user, 'avatar'),
        ]);
    }


    protected function getRequestOptions($token)
    {
        return [
            RequestOptions::HEADERS => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ];
    }
}
