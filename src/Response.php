<?php

namespace JMCode\Http;

use Psr\Http\Message\ResponseInterface;
use JMCode\Http\Filter\Response as ResponseFilter;

/**
 * Class Response
 * @package JMCode\Http
 */
class Response extends Message implements ResponseInterface
{
    /**
     * @var int
     */
    protected $statusCode = 200;
    /**
     * @var string
     */
    protected $reasonPhrase = '';

    /**
     * @return int
     */
    public function getStatusCode()
    {
        $this->statusCode;
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return ResponseInterface
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        ResponseFilter::status($code, $reasonPhrase);

        if ($this->statusCode === $code) {
            return $this;
        }

        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase;

        return $clone;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        $this->reasonPhrase;
    }
}