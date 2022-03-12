<?php

/**
 * This file is part of the Lazzard/psr7-response-sender package.
 *
 * (c) El Amrani Chakir <contact@amranich.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lazzard\Psr7ResponseSender;

use Psr\Http\Message\ResponseInterface;

/**
 * Simple PSR-7 compatible response sender class.
 *
 * @author El Amrani Chakir <contact@amranich.dev>
 */
class Sender
{

    public function __invoke(ResponseInterface $response): void
    {
        $this->send($response);
    }

    public function send(ResponseInterface $response): void
    {
        if (headers_sent()) {
            $this->sendBody($response);
            return;
        }

        $this->sendStatusLine($response)
            ->sendHeaders($response)
            ->sendBody($response);
    }

    /**
     * Send the HTTP status line contained the HTTP protocol, the status code, and the reason
     * phrase associated with the status code.
     */
    protected function sendStatusLine(ResponseInterface $response): self
    {
        header(sprintf(
            "HTTP/%s %s %s",
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ), true);

        return $this;
    }

    /**
     * Send the response headers.
     */
    protected function sendHeaders(ResponseInterface $response): self
    {
        $headers = $response->getHeaders();

        if (empty($headers)) {
            return $this;
        }

        foreach ($headers as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }

        return $this;
    }

    /**
     * Send the response body content.
     */
    protected function sendBody(ResponseInterface $response): self
    {
        $stream = $response->getBody();

        if (!$stream->isReadable()) {
            return $this;
        }

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        while (!$stream->eof()) {
            echo $stream->read(8192);
        }

        return $this;
    }
}