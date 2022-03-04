<?php

/**
 * This file is part of the Lazzard/psr7-response-sender package.
 *
 * (c) El Amrani Chakir <contact@amranich.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lazzard\Psr7ResponseSender\Tests\Integration;

use GuzzleHttp\Psr7\Response;
use Lazzard\Psr7ResponseSender\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function test_send(): void
    {
        $response = new Response(200, [
            'Content-type' => ['application/json'],
            'X-custom-header' => ['value1', 'value2']
        ], json_encode(['message' => 'hello world!']), '1.1', 'OK');

        $sender = new Sender;
        $sender($response);

        $this->expectOutputString('{"message":"hello world!"}');
        $this->assertSame(200, http_response_code());
        $this->assertSame([
            'Content-type: application/json',
            'X-custom-header: value1',
            'X-custom-header: value2'
        ], xdebug_get_headers());
    }
}