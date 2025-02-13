<?php

namespace Yiisoft\Yii\Web\Tests;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Yiisoft\Yii\Web\Cookie;

class CookieTest extends TestCase
{
    private function getCookieHeader(Cookie $cookie): string
    {
        $response = new Response();
        $response = $cookie->addToResponse($response);
        return $response->getHeaderLine('Set-Cookie');
    }

    public function testInvalidName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Cookie('test[]', 42);
    }

    public function testDefaults(): void
    {
        $cookie = new Cookie('test', 42);

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/; secure; httponly; samesite=Lax', $this->getCookieHeader($cookie));
    }

    public function testDomain(): void
    {
        $cookie = (new Cookie('test', 42))->domain('yiiframework.com');

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/; domain=yiiframework.com; secure; httponly; samesite=Lax', $this->getCookieHeader($cookie));
    }

    public function testExpireAt(): void
    {
        $expireDateTime = new \DateTime();
        $expireDateTime->setTimezone(new \DateTimeZone('GMT'));
        $formattedDateTime = $expireDateTime->format('D, d-M-Y H:i:s T');

        $cookie = (new Cookie('test', 42))->expireAt($expireDateTime);

        $this->assertSame("test=42; expires=$formattedDateTime; path=/; secure; httponly; samesite=Lax", $this->getCookieHeader($cookie));
    }

    public function testExpireWhenBrowserIsClosed(): void
    {
        $cookie = (new Cookie('test', 42))->expireWhenBrowserIsClosed();

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/; secure; httponly; samesite=Lax', $this->getCookieHeader($cookie));
    }

    public function testPath(): void
    {
        $cookie = (new Cookie('test', 42))->path('/test');

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/test; secure; httponly; samesite=Lax', $this->getCookieHeader($cookie));
    }

    public function testSecure(): void
    {
        $cookie = (new Cookie('test', 42))->secure(false);

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/; httponly; samesite=Lax', $this->getCookieHeader($cookie));
    }

    public function testHttpOnly(): void
    {
        $cookie = (new Cookie('test', 42))->httpOnly(false);

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/; secure; samesite=Lax', $this->getCookieHeader($cookie));
    }

    public function testInvalidSameSite(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Cookie('test', 42))->sameSite('invalid');
    }

    public function testSameSite(): void
    {
        $cookie = (new Cookie('test', 42))->sameSite('');

        $this->assertSame('test=42; expires=Thu, 01-Jan-1970 00:00:00 GMT; path=/; secure; httponly', $this->getCookieHeader($cookie));
    }
}
