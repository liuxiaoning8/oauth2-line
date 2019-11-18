<?php

namespace XmugenX\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class LineUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Get iss
     * https://access.line.me. URL where the ID token is generated.
     *
     * @return string
     */
    public function getId()
    {
        return $this->response['userId'];
    }

    /**
     * Get preferred display name.
     * User's display name. Not included if the profile scope was not specified in the authorization request.
     *
     * @return string
     */
    public function getName()
    {
        return $this->response['displayName'];
    }

    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        if (array_key_exists('email', $this->response)) {
            return $this->response['email'];
        }
        return $this->getId() . '@line.me';
    }

    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getPicture()
    {
        if (array_key_exists('pictureUrl', $this->response)) {
            return $this->response['pictureUrl'];
        }
        return null;
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
