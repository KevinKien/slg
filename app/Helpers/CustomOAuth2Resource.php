<?php
namespace App\Helpers;

use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\Entity\AccessTokenEntity;

class CustomOAuth2Resource extends ResourceServer
{
    /**
     * Validate a request with an access token in it.
     *
     * @param string|null $accessToken an access token to validate
     * @return bool
     */
    public function validateAccessToken($accessToken)
    {
        if (empty($accessToken)) {
            return false;
        }

        // Set the access token
        $this->accessToken = $this->getAccessTokenStorage()->get($accessToken);

        // Ensure the access token exists, Check the access token hasn't expired
        if (!$this->accessToken instanceof AccessTokenEntity || $this->accessToken->isExpired() === true) {
            return false;
        }

        return true;
    }
}