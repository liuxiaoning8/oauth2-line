<?php


namespace XmugenX\OAuth2\Client\Test\Provider;


use Eloquent\Phony\Phony;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use XmugenX\OAuth2\Client\Provider\LineUser;
use PHPUnit\Framework\TestCase;
use XmugenX\OAuth2\Client\Provider\Line;

class LineTest extends TestCase
{
    protected $provider;

    protected function setUp()
    {
        $this->provider = new Line([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
            'prompt' => 'select_account',
        ]);
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('prompt', $query);

        $this->assertEquals('select_account', $query['prompt']);

        $this->assertContains('profile', $query['scope']);
        $this->assertContains('openid', $query['scope']);



        $this->assertAttributeNotEmpty('state', $this->provider);

    }

    public function testBaseAccessTokenUrl()
    {
        $url = $this->provider->getBaseAccessTokenUrl([]);
        $uri = parse_url($url);

        $this->assertEquals('/oauth2/v2.1/token', $uri['path']);
    }


    public function testResourceOwnerDetailsUrl()
    {
        $token = $this->mockAccessToken();

        $url = $this->provider->getResourceOwnerDetailsUrl($token);

        $this->assertEquals('https://api.line.me/v2/profile', $url);
    }


    public function testUserData()
    {
        // Mock
        $response = [
            'userId' => '12345',
            'email' => 'mock.name@example.com',
            'displayName' => 'mock name',
            'pictureUrl' => 'mock_image_url',
        ];

        $token = $this->mockAccessToken();

        $provider = Phony::partialMock(Line::class);
        $provider->fetchResourceOwnerDetails->returns($response);
        $line = $provider->get();

        // Execute
        $user = $line->getResourceOwner($token);

        // Verify
        Phony::inOrder(
            $provider->fetchResourceOwnerDetails->called()
        );

        $this->assertInstanceOf('League\OAuth2\Client\Provider\ResourceOwnerInterface', $user);

        $this->assertEquals(12345, $user->getId());
        $this->assertEquals( 'mock name', $user->getName());
        $this->assertEquals('mock.name@example.com', $user->getEmail());
        $this->assertEquals('mock_image_url', $user->getPicture());

        $user = $user->toArray();

        $this->assertArrayHasKey('userId', $user);
        $this->assertArrayHasKey('displayName', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('pictureUrl', $user);
    }

    public function testErrorResponse()
    {
        // Mock
        $error_json = '{"error": {"code": 400, "message": "I am an error"}}';

        $response = Phony::mock('GuzzleHttp\Psr7\Response');
        $response->getHeader->returns(['application/json']);
        $response->getBody->returns($error_json);

        $provider = Phony::partialMock(Line::class);
        $provider->getResponse->returns($response);

        $line = $provider->get();

        $token = $this->mockAccessToken();

        // Expect
        $this->expectException(IdentityProviderException::class);

        // Execute
        $user = $line->getResourceOwner($token);

        // Verify
        Phony::inOrder(
            $provider->getResponse->calledWith($this->instanceOf('GuzzleHttp\Psr7\Request')),
            $response->getHeader->called(),
            $response->getBody->called()
        );
    }

    /**
     * @return AccessToken
     */
    private function mockAccessToken()
    {
        return new AccessToken([
            'access_token' => 'mock_access_token',
        ]);
    }

    /**
     * @param string $url
     * @return array
     */
    private function getQueryParams($url)
    {
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        return $query;
    }

}
