<?php

namespace XmugenX\OAuth2\Client\Test\Provider;

use PHPUnit\Framework\TestCase;
use XmugenX\OAuth2\Client\Provider\LineUser;

class LineUserTest extends TestCase
{
    public function testUserDefaults()
    {
        // Mock
        $user = new LineUser([
            'userId' => 12345,
            'email' => 'mock.name@example.com',
            'displayName' => 'mock name',
            'pictureUrl' => 'mock_image_url'
        ]);

        $this->assertEquals(12345, $user->getId());
        $this->assertEquals('mock name', $user->getName());
        $this->assertEquals('mock.name@example.com', $user->getEmail());
        $this->assertEquals('mock_image_url', $user->getPicture());
    }

    public function testUserPartialData()
    {
        // Mock
        $user = new LineUser([
            'userId' => 12345,
            'displayName' => 'mock name',
        ]);

        $this->assertEquals(12345, $user->getId());
        $this->assertEquals('mock name', $user->getName());
        $this->assertEquals('12345@line.me', $user->getEmail());
        $this->assertEquals(null, $user->getPicture());
    }

}
