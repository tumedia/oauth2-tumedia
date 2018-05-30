<?php

namespace Tumedia\OAuth2\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class Tumedia extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public $baseUrl = "https://auth.tumedia.no";

    public function getBaseAuthorizationUrl() : string
    {
        return "{$this->baseUrl}/oauth/authorize";
    }

    public function getBaseAccessTokenUrl(array $params) : string
    {
        return "{$this->baseUrl}/oauth/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) : string
    {
        return "{$this->baseUrl}/api/user";
    }

    public function createResourceOwner(array $response, AccessToken $token)
    {
        return $response;
    }

    public function getDefaultScopes()
    {
        return [];
    }

    public function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400 || isset($data['error'])) {
            throw new IdentityProviderException(
                $this->formatErrorMessage($data),
                $response->getStatusCode(),
                $data
            );
        }
    }

    protected function formatErrorMessage($data)
    {
        return '['.($data['error'] ?? '').']'.
            '['.($data['message'] ?? '').']'.
            '['.($data['hint'] ?? '').']';
    }
}
