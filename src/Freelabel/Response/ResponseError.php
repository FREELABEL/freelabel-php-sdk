<?php

namespace Freelabel\Response;

use Freelabel\Exceptions\AuthenticateException;

/**
 * Class ResponseError
 *
 * @package Freelabel\Common
 */
class ResponseError
{
    const EXCEPTION_MESSAGE = 'Got error response from the server: %s';

    const SUCCESS = 1;

    const REQUEST_NOT_ALLOWED = 2;

    const MISSING_PARAMS = 9;
    const INVALID_PARAMS = 10;

    const NOT_FOUND = 20;

    const NOT_ENOUGH_CREDIT = 25;

    const CHAT_API_AUTH_ERROR = 1001;

    public $errors = [];

    /**
     * Load the error data into an array.
     * Throw an exception when important errors are found.
     *
     * @param mixed $body
     *
     * @throws Exceptions\AuthenticateException
     * @throws Exceptions\BalanceException
     */
    public function __construct($body)
    {
        if (!empty($body->errors)) {
            foreach ($body->errors as $error) {
                // Product API returns errors with a "message" field instead of "description".
                // This ensures all errors have a description set.
                if (!empty($error->message)) {
                    $error->description = $error->message;
                    unset($error->message);
                }
                if (isset($error->code) && $error->code === self::REQUEST_NOT_ALLOWED) {
                    throw new AuthenticateException($this->getExceptionMessage($error));
                }

                $this->errors[] = $error;
            }
        }
    }

    /**
     * Get the exception message for the provided error.
     *
     * @param mixed $error
     *
     * @return string
     */
    private function getExceptionMessage($error)
    {
        return sprintf(self::EXCEPTION_MESSAGE, $error->description);
    }

    /**
     * Get a string of all of this response's concatenated error descriptions.
     *
     * @return string
     */
    public function getErrorString()
    {
        $errorDescriptions = [];
        foreach ($this->errors as $error) {
            $errorDescriptions[] = isset($error->description) ? $error->description : $error ;
        }
        return implode(', ', array_merge(...$errorDescriptions));
    }
}
