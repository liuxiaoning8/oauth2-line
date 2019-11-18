<?php


namespace XmugenX\OAuth2\Client\Test\Provider;


use Eloquent\Phony\Phony;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use XmugenX\OAuth2\Client\Provider\LineUser;
use PHPUnit\Framework\TestCase;
use XmugenX\OAuth2\Client\Provider\Line;

class LineScopeTest extends TestCase
{
    public function testDefaultScopes()
    {
        $provider = new Line([
            'clientId' => 'client-id',
        ]);

        $params = $this->getQueryParams($provider->getAuthorizationUrl());
        $this->assertSame('openid profile', $params['scope']);
    }

    public function testProviderScopes()
    {
        $provider = new Line([
            'clientId' => 'client-id',
            'scopes' => [
                $yt = 'https://www.googleapis.com/auth/youtube.readonly',
            ],
        ]);

        $params = $this->getQueryParams($provider->getAuthorizationUrl());

        $this->assertContains($yt, $params['scope']);
    }

    public function testOptionScopes()
    {
        $provider = new Line([
            'clientId' => 'client-id',
        ]);

        $params = $this->getQueryParams($provider->getAuthorizationUrl([
            'scope' => [
                $yt = 'https://www.googleapis.com/auth/youtube.readonly',
            ],
        ]));

        $this->assertContains($yt, $params['scope']);
    }

    private function getQueryParams($url)
    {
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        return $query;
    }
}
