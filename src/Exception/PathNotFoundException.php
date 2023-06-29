<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 * Date: 2022/11/18
 * Time: 下午8:03
 */

namespace Guirong\PhpRouter\Exception;

class PathNotFoundException extends \RuntimeException
{
    private $statusCode;

    private $headers;

    /**
     * Undocumented function
     *
     * @param string $message
     * @param integer $statusCode
     * @param \Exception|null $previous
     * @param array $headers
     * @param integer $code
     * @return array
     */
    public function __construct(string $message = null, int $statusCode = 404, \Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers    = $headers;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
