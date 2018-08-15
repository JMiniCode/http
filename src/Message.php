<?php

namespace JMCode\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use JMCode\Http\Filter\Message as MessageFilter;

abstract class Message implements MessageInterface
{
    protected $protocolVersion = '1.1';
    protected $headers = [];
    protected $body;

    public function getProtocolVersion()
    {
        $this->protocolVersion;
    }

    public function withProtocolVersion($version)
    {
        MessageFilter::protocolVersion($version);

        if ($this->protocolVersion === $version) {
            return $this;
        }

        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    public function getHeaders()
    {

    }

    public function hasHeader($name)
    {

    }

    public function getHeader($name)
    {

    }

    public function getHeaderLine($name)
    {

    }

    public function withHeader($name, $value)
    {

    }

    public function withAddedHeader($name, $value)
    {

    }

    public function withoutHeader($name)
    {

    }

    public function getBody()
    {

    }

    public function withBody(StreamInterface $body)
    {

    }
}