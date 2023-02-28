<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 * Date: 2022/11/18
 * Time: 下午8:03
 */

namespace Guirong\Route\Response;


/**
 * Class Response
 * @package Guirong\Route\Response
 */
class Response
{

    protected $content;

    protected $original;

    protected $header = [];

    /**
     * Set output header
     *
     * @return void
     */
    protected function setHeader(array $value = [])
    {
        $this->header = $value;
        return $this;
    }

    protected function getHeader($key = '')
    {
        if ($key === '') {
            return $this->header;
        }
        return $this->header[$key] ?? null;
    }

    /**
     * Set output header
     *
     * @return void
     */
    protected function sendHeader()
    {
        if (!headers_sent() && !empty($this->header)) {
            http_response_code(200);
            foreach ($this->header as $name => $val) {
                header($name . ':' . $val);
            }
        }
    }

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return void
     */
    public function __construct($data = null)
    {
        $this->original = $data;
        $this->content = $data;
        $this->setHeader(
            [
                'Content-Type' => 'text/javascript'
            ]
        );
    }

    /**
     * Gets the origin response content.
     *
     * @return string|false
     */
    public function geOriginalContent()
    {
        return $this->original;
    }

    /**
     * Gets the current response content.
     *
     * @return string|false
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the response content.
     *
     * Valid types are strings, numbers, null, and objects that implement a __toString() method.
     *
     * @param mixed $content Content that can be cast to string
     *
     * @return $this
     *
     * @throws \UnexpectedValueException
     */
    public function setContent($content)
    {
        if (null !== $content && !\is_string($content) && !is_numeric($content) && !\is_callable([$content, '__toString'])) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.', \gettype($content)));
        }

        $this->content = (string) $content;

        return $this;
    }

    /**
     * Returns the Response as an HTTP string.
     *
     * The string representation of the Response is the same as the
     * one that will be sent to the client only if the prepare() method
     * has been called before.
     *
     * @return string The Response as an HTTP string
     */
    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return $this
     */
    public function send()
    {
        $this->sendHeader();

        $this->sendContent();

        if (\function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif (!\in_array(\PHP_SAPI, ['cli', 'phpdbg'], true)) {
            static::closeOutputBuffers(0, true);
        }

        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    /**
     * Cleans or flushes output buffers up to target level.
     *
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     *
     * @final
     */
    public static function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $status = ob_get_status(true);
        $level = \count($status);
        $flags = \PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? \PHP_OUTPUT_HANDLER_FLUSHABLE : \PHP_OUTPUT_HANDLER_CLEANABLE);

        while ($level-- > $targetLevel && ($s = $status[$level]) && (!isset($s['del']) ? !isset($s['flags']) || ($s['flags'] & $flags) === $flags : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }

    /**
     * Response processing and return the instance
     *
     * @param object $response
     * @return object
     */
    public function toResponse($response)
    {
        if ($response instanceof JsonResponse) {
            //...
        } else if (is_array($response)) {
            $response = new JsonResponse($response);
        } elseif (!$response instanceof Response) {
            $response = new Response($response);
        }
        return $response;
    }
}
