<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 * Date: 2022/11/18
 * Time: 下午8:03
 */

namespace Guirong\PhpRouter\Response;

class HeaderBag
{
    protected const UPPER = '_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected const LOWER = '-abcdefghijklmnopqrstuvwxyz';

    protected $headers = [];
    protected $cacheControl = [];

    public function __construct(array $headers = [])
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Returns the headers as a string.
     *
     * @return string The headers
     */
    public function __toString()
    {
        if (!$headers = $this->all()) {
            return '';
        }

        ksort($headers);
        $max = max(array_map('strlen', array_keys($headers))) + 1;
        $content = '';
        foreach ($headers as $name => $values) {
            $name = ucwords($name, '-');
            foreach ($values as $value) {
                $content .= sprintf("%-{$max}s %s\r\n", $name . ':', $value);
            }
        }

        return $content;
    }

    /**
     * Returns the headers.
     *
     * @param string|null $key The name of the headers to return or null to get them all
     *
     * @return array An array of headers
     */
    public function all(/* string $key = null */)
    {
        if (1 <= \func_num_args() && null !== $key = func_get_arg(0)) {
            return $this->headers[strtr($key, self::UPPER, self::LOWER)] ?? [];
        }

        return $this->headers;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($this->all());
    }

    /**
     * Replaces the current HTTP headers by a new set.
     */
    public function replace(array $headers = [])
    {
        $this->headers = [];
        $this->add($headers);
    }

    /**
     * Adds new headers the current HTTP headers set.
     */
    public function add(array $headers)
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Returns a header value by name.
     *
     * @param string      $key     The header name
     * @param string|null $default The default value
     *
     * @return string|null The first header value or default value
     */
    public function get($key, $default = null)
    {
        $headers = $this->all((string) $key);
        if (2 < \func_num_args()) {
            @trigger_error(sprintf('Passing a third argument to "%s()" is deprecated since Symfony 4.4, use method "all()" instead', __METHOD__), \E_USER_DEPRECATED);

            if (!func_get_arg(2)) {
                return $headers;
            }
        }

        if (!$headers) {
            return $default;
        }

        if (null === $headers[0]) {
            return null;
        }

        return (string) $headers[0];
    }

    /**
     * Sets a header by name.
     *
     * @param string               $key     The key
     * @param string|string[]|null $values  The value or an array of values
     * @param bool                 $replace Whether to replace the actual value or not (true by default)
     */
    public function set($key, $values, $replace = true)
    {
        $key = strtr($key, self::UPPER, self::LOWER);

        if (\is_array($values)) {
            $values = array_values($values);

            if (true === $replace || !isset($this->headers[$key])) {
                $this->headers[$key] = $values;
            } else {
                $this->headers[$key] = array_merge($this->headers[$key], $values);
            }
        } else {
            if (true === $replace || !isset($this->headers[$key])) {
                $this->headers[$key] = [$values];
            } else {
                $this->headers[$key][] = $values;
            }
        }
    }

    /**
     * Returns true if the HTTP header is defined.
     *
     * @param string $key The HTTP header
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return \array_key_exists(strtr($key, self::UPPER, self::LOWER), $this->all());
    }

    /**
     * Returns true if the given HTTP header contains the given value.
     *
     * @param string $key   The HTTP header name
     * @param string $value The HTTP value
     *
     * @return bool true if the value is contained in the header, false otherwise
     */
    public function contains($key, $value)
    {
        return \in_array($value, $this->all((string) $key));
    }

    /**
     * Removes a header.
     *
     * @param string $key The HTTP header name
     */
    public function remove($key)
    {
        $key = strtr($key, self::UPPER, self::LOWER);

        unset($this->headers[$key]);
    }
}
