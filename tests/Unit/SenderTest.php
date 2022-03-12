<?php

/**
 * This file is part of the Lazzard/psr7-response-sender package.
 *
 * (c) El Amrani Chakir <contact@amranich.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lazzard\Psr7ResponseSender\Tests\Unit;

use Lazzard\Psr7ResponseSender\Sender;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class SenderTest extends TestCase
{
    use PHPMock;

    public function test_send(): void
    {
        $response = $this->getMockBuilder(ResponseInterface::class)
            ->onlyMethods([
                'getProtocolVersion',
                'getStatusCode',
                'getReasonPhrase',
                'getHeaders',
                'getBody'
            ])
            ->getMockForAbstractClass();

        $response->expects($this->once())
            ->method('getProtocolVersion')
            ->willReturn('1.1');

        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects($this->once())
            ->method('getReasonPhrase')
            ->willReturn('OK');

        $response->expects($this->once())
            ->method('getReasonPhrase')
            ->willReturn('OK');

        $response->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Content-type' => ['application/json'],
                'X-custom-header' => ['value1', 'value2']
            ]);
        
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->getMockedStream());

        $this->expectOutputString("Hello world!");

        $headers_sent_func = $this->getFunctionMock('Lazzard\Psr7ResponseSender', 'headers_sent');
        $headers_sent_func->expects($this->any())
            ->willReturn(false);

        $header_func = $this->getFunctionMock('Lazzard\Psr7ResponseSender', 'header');
        $header_func->expects($this->exactly(4))
            ->withConsecutive(
            ['HTTP/1.1 200 OK', true],
            ['Content-type: application/json', false],
            ['X-custom-header: value1', false],
            ['X-custom-header: value2', false]
        );

        $sender = new Sender;
        $sender->send($response);
    }

    public function test_send_headers_already_sent(): void
    {
        $response = $this->getMockBuilder(ResponseInterface::class)
            ->onlyMethods(['getBody'])
            ->getMockForAbstractClass();


        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->getMockedStream());

        $this->expectOutputString("Hello world!");

        $headers_sent_func = $this->getFunctionMock('Lazzard\Psr7ResponseSender', 'headers_sent');
        $headers_sent_func->expects($this->any())
            ->willReturn(true);

        $sender = new Sender;
        $sender->send($response);
    }

    protected function getMockedStream(): StreamInterface
    {
        $stream = $this->getMockBuilder(StreamInterface::class)
            ->onlyMethods([
                'isReadable',
                'isSeekable',
                'rewind',
                'eof',
                'read'
            ])
            ->getMockForAbstractClass();

        $stream->expects($this->once())
            ->method('isReadable')
            ->willReturn(true);

        $stream->expects($this->once())
            ->method('isSeekable')
            ->willReturn(true);

        $stream->expects($this->once())
            ->method('rewind')
            ->willReturn(true);

        $stream->expects($this->atLeastOnce())
            ->method('eof')
            ->willReturnOnConsecutiveCalls(false, true);

        $stream->expects($this->once())
            ->method('read')
            ->willReturn('Hello world!');

        return $stream;
    }
}