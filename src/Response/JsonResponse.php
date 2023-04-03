<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 * Date: 2022/11/18
 * Time: 下午8:03
 */

namespace Guirong\PhpRouter\Response;

use InvalidArgumentException;

class JsonResponse extends Response
{
    protected $data;

    // Encode <, >, ', &, and " characters in the JSON, making it also safe to be embedded into HTML.
    // 15 === JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    public const DEFAULT_ENCODING_OPTIONS = 15;

    protected $encodingOptions = self::DEFAULT_ENCODING_OPTIONS;

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int    $options
     * @param  int    $status
     * @param  array  $headers
     * @return void
     */
    public function __construct($data = null, $options = 0,$status = 200, array $headers = [])
    {
        parent::__construct($data,$status,$headers);
        $this->encodingOptions = $options;
        $this->setData($data);
    }

    /**
     * Get the json_decoded data from the response.
     *
     * @param  bool  $assoc
     * @param  int  $depth
     * @return mixed
     */
    public function getData($assoc = false, $depth = 512)
    {
        return json_decode($this->data, $assoc, $depth);
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data = [])
    {
        $this->original = $data;

        $this->data = json_encode($data, $this->encodingOptions);

        if (!$this->hasValidJson(json_last_error())) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        $this->setHeaderContentType();

        return $this->setContent($this->data);
    }

    /**
     * Set headers Content-Type
     *
     * @return $this
     */
    protected function setHeaderContentType()
    {
        if (!$this->headers->has('Content-Type') || 'text/javascript' === $this->headers->get('Content-Type')) {
            $this->headers->set('Content-Type', 'application/json');
        }
        return $this;
    }
    /**
     * Determine if an error occurred during JSON encoding.
     *
     * @param  int  $jsonError
     * @return bool
     */
    protected function hasValidJson($jsonError)
    {
        if ($jsonError === JSON_ERROR_NONE) {
            return true;
        }

        return $this->hasEncodingOption(JSON_PARTIAL_OUTPUT_ON_ERROR) &&
            in_array($jsonError, [
                JSON_ERROR_RECURSION,
                JSON_ERROR_INF_OR_NAN,
                JSON_ERROR_UNSUPPORTED_TYPE,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setEncodingOptions($options)
    {
        $this->encodingOptions = (int) $options;

        return $this->setData($this->getData());
    }

    /**
     * Determine if a JSON encoding option is set.
     *
     * @param  int  $option
     * @return bool
     */
    public function hasEncodingOption($option)
    {
        return (bool) ($this->encodingOptions & $option);
    }
}
