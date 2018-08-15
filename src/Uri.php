<?php

namespace JMCode\Http;

use Psr\Http\Message\UriInterface;
use JMCode\Http\Filter\Uri as UriFilter;

/**
 * Class Uri
 * @package JMCode\Http
 */
class Uri implements UriInterface
{
    /**
     * @var string
     */
    const HTTP_DEFAULT_HOST = 'localhost';

    /**
     * @var string
     */
    protected $scheme = '';
    /**
     * @var string
     */
    protected $userInfo = '';
    /**
     * @var string
     */
    protected $host = '';
    /**
     * @var null
     */
    protected $port = null;
    /**
     * @var string
     */
    protected $path = '/';
    /**
     * @var string
     */
    protected $query = '';
    /**
     * @var string
     */
    protected $fragment = '';

    /**
     * @return string
     */
    public function __toString()
    {
        $scheme = $this->getScheme();
        $authority = $this->getAuthority();
        $path = $this->getPath();
        $query = $this->getQuery();
        $fragment = $this->getFragment();

        return implode("", [
            $scheme ? $scheme . ':' : '',
            $authority ? '//' . $authority : '',
            $path,
            $query ? '?' . $query : '',
            $fragment ? '#' . $fragment : ''
        ]);
    }

    /**
     * Create URI from string
     *
     * @param string $uri
     * @return UriInterface
     */
    public static function createFromString($uri)
    {
        if (!is_string($uri) && !method_exists($uri, '__toString')) {
            throw new InvalidArgumentException('Uri must be a string');
        }

        return self::createFromParse(parse_url($uri));
    }

    /**
     * Create URI from a hash of `parse_url` components.
     *
     * @param array $parts
     * @return UriInterface
     */
    public static function createFromParse(array $parts)
    {
        $uri = new static();
        if (isset($parts['schema'])) {
            $uri->scheme = UriFilter::scheme($parts['schema']);
        }
        if (isset($parts['host'])) {
            $uri->host = UriFilter::host($parts['host']);
        }
        if (isset($parts['port'])) {
            $uri->port = UriFilter::port($parts['port']);
        }
        if (isset($parts['path'])) {
            $uri->path = UriFilter::path($parts['path']);
        }
        if (isset($parts['query'])) {
            $uri->query = UriFilter::body($parts['query']);
        }
        if (isset($parts['fragment'])) {
            $uri->fragment = UriFilter::body($parts['fragment']);
        }
        if (isset($parts['user'])) {
            $uri->userInfo = $parts['user'];
        }
        if (isset($parts['pass'])) {
            $uri->userInfo .= ':' . $parts['pass'];
        }
        $uri->validate();
        return $uri;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getAuthority()
    {
        $authority = $this->host;

        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * @return string
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param string $scheme
     * @return UriInterface
     */
    public function withScheme($scheme)
    {
        UriFilter::scheme($scheme);

        if ($this->scheme === $scheme) {
            return $this;
        }

        $clone = clone $this;
        $clone->scheme = $scheme;
        $clone->validate();

        return $clone;
    }

    /**
     * @param string $user
     * @param null $password
     * @return UriInterface
     */
    public function withUserInfo($user, $password = null)
    {
        UriFilter::userInfo($user, $password);

        $userInfo = $user;
        if ($password !== null) {
            $userInfo .= ':' . $password;
        }

        if ($this->userInfo === $userInfo) {
            return $this;
        }

        $clone = clone $this;
        $clone->userInfo = $userInfo;
        $clone->validate();

        return $clone;
    }

    /**
     * @param string $host
     * @return UriInterface
     */
    public function withHost($host)
    {
        UriFilter::host($host);

        if ($this->host === $host) {
            return $this;
        }

        $clone = clone $this;
        $clone->host = $host;
        $clone->validate();

        return $clone;
    }

    /**
     * @param int|null $port
     * @return UriInterface
     */
    public function withPort($port)
    {
        UriFilter::port($port);

        if ($this->port === $port) {
            return $this;
        }

        $clone = clone $this;
        $clone->port = $port;
        $clone->validate();

        return $clone;
    }

    /**
     * @param string $path
     * @return UriInterface
     */
    public function withPath($path)
    {
        UriFilter::path($path);

        if ($this->path === $path) {
            return $this;
        }

        $clone = clone $this;
        $clone->path = $path;
        $clone->validate();

        return $clone;
    }

    /**
     * @param string $query
     * @return UriInterface
     */
    public function withQuery($query)
    {
        UriFilter::body($query);

        if ($this->query === $query) {
            return $this;
        }

        $clone = clone $this;
        $clone->query = $query;

        return $clone;
    }

    /**
     * @param string $fragment
     * @return UriInterface
     */
    public function withFragment($fragment)
    {
        UriFilter::body($fragment);

        if ($this->fragment === $fragment) {
            return $this;
        }

        $clone = clone $this;
        $clone->fragment = $fragment;

        return $clone;
    }

    /**
     *
     */
    private function validate()
    {
        if ($this->host === '' && ($this->scheme === 'http' || $this->scheme === 'https')) {
            $this->host = self::HTTP_DEFAULT_HOST;
        }

        if ($this->getAuthority() === '') {
            if (0 === strpos($this->path, '//')) {
                throw new \InvalidArgumentException('The path of a URI without an authority must not start with two slashes "//"');
            }
            if ($this->scheme === '' && false !== strpos(explode('/', $this->path, 2)[0], ':')) {
                throw new \InvalidArgumentException('A relative URI must not have a path beginning with a segment containing a colon');
            }
        } elseif (isset($this->path[0]) && $this->path[0] !== '/') {
            throw new \InvalidArgumentException('The path of a URI with an authority must start with a slash "/" or be empty');
        }
    }
}