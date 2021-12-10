<?php

namespace Freelabel\Resource\Base;

use Freelabel\Common;
use Freelabel\Exceptions;
use Freelabel\Http\HttpClient;
use Freelabel\Model\Base\BaseModelList;
use Freelabel\Response\ResponseError;

/**
 * Class Base
 *
 * @package Freelabel\Resources
 */
class BaseResource
{
    /**
     * @var \Freelabel\Common\HttpClient
     */
    protected $httpClient;

    /**
     * @var string The resource name as it is known at the server
     */
    protected $resourceUrl;

    /**
     * @var Objects\Hlr|Objects\Message|Objects\Balance|Objects\Verify|Objects\Lookup|Objects\VoiceMessage|Objects\Conversation\Conversation
     */
    protected $model;

    /**
     * @var Objects\MessageResponse
     */
    protected $responseObject;

    /**
     * @param Common\HttpClient $httpClient
     */
    public function __construct(\Freelabel\Http\HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param mixed $resourceUrl
     *
     * @return void
     */
    public function setResourceUrl($resourceUrl): void
    {
        $this->resourceUrl = $resourceUrl;
    }

    /**
     * @return string
     */
    public function getResourceUrl()
    {
        return $this->resourceUrl;
    }

    /**
     * @param mixed $model
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return Objects\Balance|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\Message|Objects\Verify|Objects\VoiceMessage
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $responseObject
     *
     * @return void
     */
    public function setResponseObject($responseObject): void
    {
        $this->responseObject = $responseObject;
    }

    /**
     * @return Objects\MessageResponse
     */
    public function getResponseObject()
    {
        return $this->responseObject;
    }

    /**
     * @no-named-arguments
     *
     * @param mixed $model
     * @param array|null $query
     *
     * @return Objects\Balance|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\MessageResponse|Objects\Verify|Objects\VoiceMessage|null
     *
     * @throws Exceptions\HttpException
     * @throws Exceptions\RequestException
     * @throws Exceptions\ServerException
     */
    public function create($model, $query = null)
    {
        $body = json_encode($model);
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_POST, $this->resourceUrl, $query, $body);

        return $this->processRequest($status, $body);
    }

    /**
     * @param array|null $parameters
     *
     * @return Objects\Balance|Objects\BaseList|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\Message|Objects\Verify|Objects\VoiceMessage|null
     */
    public function getListWithPagination(array $parameters =  [])
    {
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_GET, $this->resourceUrl, $parameters);

        if ($status === 200) {
            $body = json_decode($body);
            $items = $body->data;
            unset($body->data);

            $baseList = new BaseModelList();
            $baseList->count = $body->lastPage;
            $baseList->links;
            $baseList->loadFromArray($body);

            $modelName = $this->model;
            foreach ($items as $item) {
                /** @psalm-suppress UndefinedClass */
                $model = new $modelName($this->httpClient);

                $message           = $model->loadFromArray($item);
                $baseList->items[] = $message;
            }
            return $baseList;
        }

        return $this->processRequest($status, $body);
    }
    public function getList(array $parameters =  [])
    {
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_GET, $this->resourceUrl, $parameters);

        if ($status === 200) {
            $body = json_decode($body);
            $items = $body->data;
            unset($body->data);

            $baseList = new BaseModelList();
            $baseList->loadFromArray($body);

            $modelName = $this->model;
            foreach ($items as $item) {
                /** @psalm-suppress UndefinedClass */
                $model = new $modelName($this->httpClient);

                $message           = $model->loadFromArray($item);
                $baseList->items[] = $message;
            }
            return $baseList;
        }

        return $this->processRequest($status, $body);
    }

    /**
     * @no-named-arguments
     *
     * @param mixed $id
     *
     * @return Objects\Balance|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\Message|Objects\Verify|Objects\VoiceMessage|null
     *
     * @throws Exceptions\RequestException
     * @throws Exceptions\ServerException
     */
    public function read($id = null)
    {
        $resourceUrl = $this->resourceUrl . (($id) ? '/' . $id : null);
        list(, , $body) = $this->httpClient->sendHttpRequest(HttpClient::REQUEST_GET, $resourceUrl);

        return $this->processRequest($body);
    }

    /**
     * @param mixed $id
     *
     * @return Objects\Balance|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\Message|Objects\Verify|Objects\VoiceMessage|null|true
     *
     * @throws Exceptions\RequestException
     * @throws Exceptions\ServerException
     */
    public function delete($id)
    {
        $resourceUrl = $this->resourceUrl . '/' . $id;
        list($status, $headers , $body) = $this->httpClient->performHttpRequest(\Freelabel\Http\HttpClient::REQUEST_DELETE, $resourceUrl);

        if ($status === HttpClient::HTTP_NO_CONTENT) {
            return true;
        }

        return $this->processRequest($status, $body);
    }

    /**
     * @param string $body
     *
     * @return Objects\Balance|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\Message|Objects\Verify|Objects\VoiceMessage|Objects\MessageResponse|null
     *
     * @throws \Freelabel\Exceptions\RequestException
     * @throws \Freelabel\Exceptions\ServerException
     */
    public function processRequest($status, $body)
    {

        if ($status === HttpClient::SERVER_ERROR) {
            throw new Exceptions\ServerException('Server was unable to handle this request');
        }
        if ($status === HttpClient::HTTP_UNAUTHORIZED) {
            throw new Exceptions\AuthenticateException('You are not authorized to perform this action');
        }
        $body = @json_decode($body);
        if ($body === null || $body === false) {
            throw new Exceptions\ServerException('Got an invalid JSON response from the server.');
        }

        if (!empty($body->errors)) {
            $responseError = new ResponseError($body);
            throw new Exceptions\RequestException($responseError->getErrorString());
        }

        if ($this->responseObject) {
            return $this->responseObject->loadFromArray($body);
        }

        return $this->model->loadFromArray($body->data);
    }

    /**
     * @param mixed $model
     * @param mixed $id
     *
     * @return Objects\Balance|Objects\Conversation\Conversation|Objects\Hlr|Objects\Lookup|Objects\Message|Objects\Verify|Objects\VoiceMessage|null ->object
     *
     * @internal param array $parameters
     */
    public function update($model, $id)
    {
        $objVars = get_object_vars($model);
        $body = [];
        foreach ($objVars as $key => $value) {
            if ($value !== null) {
                $body[$key] = $value;
            }
        }

        $resourceUrl = $this->resourceUrl . ($id ? '/' . $id : null);
        $body = json_encode($body);

        list(, , $body) = $this->httpClient->performHttpRequest(HttpClient::REQUEST_PUT, $resourceUrl, false, $body);
        return $this->processRequest($body);
    }

    /**
     * @return Common\HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
