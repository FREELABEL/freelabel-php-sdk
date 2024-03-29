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

    public function create($model, $query = null)
    {
        $body = json_encode($model);
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_POST, $this->resourceUrl, $query, $body);


        return $this->processRequest($status, $body);
    }

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

    public function read($id = null)
    {
        $resourceUrl = $this->resourceUrl . (($id) ? '/' . $id : null);
        list($status, , $body) = $this->httpClient->sendHttpRequest(HttpClient::REQUEST_GET, $resourceUrl);

        return $this->processRequest($status,$body);
    }

    public function delete($id)
    {
        $resourceUrl = $this->resourceUrl . '/' . $id;
        list($status, $headers , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_DELETE, $resourceUrl);

        if ($status === HttpClient::HTTP_NO_CONTENT) {
            return true;
        }
        if ($status === HttpClient::NOT_FOUND) {
            return false;
        }
        return $this->processRequest($status, $body);
    }

    public function processRequest($status, $body)
    {

        $body = json_decode($body);
        $exceptionCode = null;
        if (!empty($body->error_code)){
            $exceptionCode = $body->error_code;
        }

        if ($status === HttpClient::SERVER_ERROR) {
            throw new Exceptions\ServerException('Server was unable to handle this request' . $exceptionCode);
        }
        if ($status === HttpClient::NOT_FOUND) {
            throw new Exceptions\ServerException('Not found exception' . $exceptionCode);
        }
        if ($status === HttpClient::HTTP_UNAUTHORIZED) {
            throw new Exceptions\AuthenticateException('You are not authorized to perform this action' . $exceptionCode);
        }

        if ($body === null || $body === false) {
            throw new Exceptions\ServerException('Got an invalid JSON response from the server.');
        }


        if (!empty($body->error_code)){
            throw new Exceptions\ServerException($body->error_code);
        }

        if (!empty($body->errors)) {
            $responseError = new ResponseError($body);
            throw new Exceptions\RequestException($responseError->getErrorString());
        }

        if ($this->responseObject) {
            return $this->responseObject->loadFromArray($body);
        }


        if (!empty($body->data)) {
            return $this->model->loadFromArray($body->data);
        }

        throw new Exceptions\RequestException('Bad response' . $exceptionCode);

    }

    public function processResponse($status, $body)
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

        throw new Exceptions\ServerException('Something went wrong. Please try again.');
    }


    public function update($model, $id = null)
    {

        $body = [];
        if (is_object($model)) {
            if ($model->id) {
                $id = $model->id;
            }
            $objVars = get_object_vars($model);
            $body = [];
            foreach ($objVars as $key => $value) {
                if ($value !== null) {
                    $body[$key] = $value;
                }
            }
            $body = json_encode($model);
        }
        if (is_array($model)) {
            $id = isset($model['id']) ? $model['id'] : $id;
            $body = json_encode($model);
        }
        $resourceUrl = $this->resourceUrl . ($id ? '/' . $id : null);

        list($status, , $body) = $this->httpClient->sendHttpRequest(HttpClient::REQUEST_PUT, $resourceUrl, true, $body);
        return $this->processRequest($status, $body);
    }

    /**
     * @return Common\HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
