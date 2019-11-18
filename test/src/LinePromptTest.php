<?php


namespace XmugenX\OAuth2\Client\Test\Provider;


use Eloquent\Phony\Phony;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use XmugenX\OAuth2\Client\Provider\LineUser;
use PHPUnit\Framework\TestCase;
use XmugenX\OAuth2\Client\Provider\Line;



class LinePromptTest extends TestCase
{
    public function testDefaultParameters()
    {
        $provider = new Line([
            'clientId' => 'client-id',
        ]);

        $params = $this->getQueryParams($provider->getAuthorizationUrl());

        $this->assertArrayHasKey('approval_prompt', $params);
    }

    public function testPromptParameters()
    {
        $provider = new Line([
            'clientId' => 'client-id',
            'prompt' => 'consent',
        ]);

        $params = $this->getQueryParams($provider->getAuthorizationUrl());

        $this->assertArrayNotHasKey('approval_prompt', $params);
    }

    private function getQueryParams($url)
    {
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        return $query;
    }
}
