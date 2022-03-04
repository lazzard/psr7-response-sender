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
    /** @var ResponseInterface */
    protected $response;

    public function __invoke(ResponseInterface $response): void
    {
        $this->response = $response;

        if (headers_sent()) {
            $this->sendBody();
            return;
        }

        $this->sendStatusLine()
            ->sendHeaders()
            ->sendBody();
    }

    /**
     * Send the HTTP status line contained the HTTP protocol, the status code, and the reason
     * phrase associated with the status code.
     */
    protected function sendStatusLine(): self
    {
        header(sprintf(
            "HTTP/%s %s %s",
            $this->response->getProtocolVersion(),
            $this->response->getStatusCode(),
            $this->response->getReasonPhrase()
        ), true);

        return $this;
    }

    /**
     * Send the response headers.
     */
    protected function sendHeaders(): self
    {
        $headers = $this->response->getHeaders();

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
    protected function sendBody(): self
    {
        $stream = $this->response->getBody();

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