<?php

namespace JMCode\Http\Filter;

abstract class Uri
{
    private static $charUnreserved = 'a-zA-Z0-9_\-\.~';
    private static $charSubDelims = '!\$&\'\(\)\*\+,;=';

    public static function scheme(&$scheme)
    {
        if (!is_string($scheme)) {
            throw new \InvalidArgumentException('Scheme must be a string');
        }

        $scheme = strtolower($scheme);
    }

    public static function userInfo(&$user, &$password = '')
    {
        if (!is_string($user)) {
            throw new \InvalidArgumentException('User must be a string');
        }

        if (!is_string($password)) {
            throw new \InvalidArgumentException('Password must be a string');
        }

        $swap = function ($match) {
            return rawurlencode($match[0]);
        };

        $user = preg_replace_callback(
            '/(?:[^' . self::$charUnreserved . self::$charSubDelims . '%:@\/]++|%(?![A-Fa-f0-9]{2}))/u',
            $swap,
            $user
        );

        $password = preg_replace_callback(
            '/(?:[^' . self::$charUnreserved . self::$charSubDelims . '%:@\/]++|%(?![A-Fa-f0-9]{2}))/u',
            $swap,
            $password
        );
    }

    public static function host(&$host)
    {
        if (!is_string($host)) {
            throw new \InvalidArgumentException('Host must be a string');
        }

        $host = strtolower($host);
    }

    public static function port(&$port)
    {
        if ($port === null) {
            return null;
        }

        $port = (int)$port;
        if (1 > $port || 0xffff < $port) {
            throw new \InvalidArgumentException(
                sprintf('Invalid port: %d. Must be between 1 and 65535', $port)
            );
        }
    }

    public static function path(&$path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException('Path must be a string');
        }

        $path = preg_replace_callback(
            '/(?:[^' . self::$charUnreserved . self::$charSubDelims . '%:@\/]++|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $path
        );
    }

    public static function body(&$body)
    {
        if (!is_string($body)) {
            throw new \InvalidArgumentException('Body must be a string');
        }

        $body = preg_replace_callback(
            '/(?:[^' . self::$charUnreserved . self::$charSubDelims . '%:@\/\?]++|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $body
        );
    }
}