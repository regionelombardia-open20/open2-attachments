<?php

namespace open20\amos\attachments\utility;

use open20\amos\attachments\exceptions\ShutterstockException;
use open20\amos\attachments\FileModule;
use open20\amos\core\response\Response;
use open20\amos\utility\models\PlatformConfigs;
use yii\httpclient\Client;
use yii\base\Model;
use yii\httpclient\Request;

class ShutterstockClient extends Model
{
    public $enableSandbox = false;
    private $clientId;
    private $clientSecret;
    private $access_token = '';

    private $baseUrlApi = 'https://api.shutterstock.com/v2';
    private $baseUrlApiSandbox = 'https://api-sandbox.shutterstock.com/v2';
    private $client;

    public $errorMessage;

    const PLATFORM_CONFIGS_ACCESS_TOKEN = 'shutterstock_access_token';
    const PLATFORM_CONFIGS_DOWNLOADS_LEFT = 'shutterstock_downloads_left';
    const PLATFORM_CONFIGS_DOWNLOADS_EXPIRE = 'shutterstock_downloads_expire';

    public function init()
    {
        parent::init();
        $module = \Yii::$app->getModule('attachments');
        if ($module && !empty($module->shutterstockConfigs['clientId']) && !empty($module->shutterstockConfigs['clientSecret'])) {
            $this->setAccessTokenFromConfigs($module);
            if (isset($module->shutterstockConfigs['enableSandbox'])) {
                $this->enableSandbox = $module->shutterstockConfigs['enableSandbox'];
            }
            $this->clientId = $module->shutterstockConfigs['clientId'];
            $this->clientSecret = $module->shutterstockConfigs['clientSecret'];
            $this->client = new Client();
        }
    }

    /**
     * @return void
     */
    public function setAccessTokenFromConfigs($module)
    {
        $platformConfig = PlatformConfigs::find()->andWhere(['module' => 'attachments', 'key' => self::PLATFORM_CONFIGS_ACCESS_TOKEN])->one();
        if ($platformConfig) {
            $this->access_token = $platformConfig->value;
        } else {
            if (!empty($module->shutterstockConfigs['access_token'])) {
                $this->access_token = $module->shutterstockConfigs['access_token'];
            }
        }
    }

    public function authorize()
    {
        $client = $this->client;
        $request = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->baseUrlApi . '/oauth/authorize')
            ->addHeaders(['user-agent' => 'php/curl'])
            ->setOptions([
                CURLOPT_USERAGENT => "php/curl",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION, true
            ])
            ->setData([
                "scope" => "proscriptions.create,proscriptions.view,purchases.view",
                "state" => "done_" . microtime(true),
                "response_type" => "code",
//                "redirect_uri" => str_replace('https://','',\Yii::$app->params['platform']['frontendUrl'] . '/attachments/shutterstock/auth'),
                "redirect_uri" => \Yii::$app->params['platform']['frontendUrl'] . '/attachments/shutterstock/auth',
                "client_id" => $this->clientId
            ]);

        $response = $request->send();


        $urlRedirect = $response->headers['location'];
        if (!empty($urlRedirect)) {
            return \Yii::$app->controller->redirect($urlRedirect);
        }
        //go home
        return \Yii::$app->controller->redirect('');
    }

    /**
     * @param $code
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function generateAccessToken($code)
    {
        $client = $this->client;
        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->baseUrlApi . '/images/search')
            ->addHeaders(['user-agent' => 'php/curl'])
            ->addHeaders(['Content-Type' => 'application/json'])
            ->setOptions([
                CURLOPT_USERAGENT => "php/curl",
                CURLOPT_RETURNTRANSFER => 1,
            ])
            ->setData([
                "client_id" => $this->clientId,
                "client_secret" => $this->clientSecret,
                "grant_type" => "authorization_code",
                "expires" => false,
                "code" => $code
            ]);
        $response = $request->send();
    }

    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function refreshToken()
    {
        $client = $this->client;
        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->baseUrlApi . '/images/search')
            ->addHeaders(['user-agent' => 'php/curl'])
            ->addHeaders(['Content-Type' => 'application/json'])
            ->setOptions([
                CURLOPT_USERAGENT => "php/curl",
                CURLOPT_RETURNTRANSFER => 1,
            ])
            ->setData([
                "client_id" => $this->clientId,
                "client_secret" => $this->clientSecret,
                "grant_type" => "refresh_token",
                "refresh_token" => $this->access_token
            ]);
        $response = $request->send();
    }

    /**
     * @param $api
     * @param $params
     * @return array|mixed
     */
    public function callApi($api, $method = 'GET', $params = [], $useSandbox = false, $a = false)
    {
        $client = new Client();
        $baseApiUrl = $this->baseUrlApi;
        if ($useSandbox && $this->enableSandbox) {
            $baseApiUrl = $this->baseUrlApiSandbox;
        }

        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($baseApiUrl . $api)
            ->setOptions([
                CURLOPT_USERAGENT => "php/curl",
                CURLOPT_RETURNTRANSFER => 1,
            ])
            ->setData($params)
            ->addHeaders([
                'user-agent' => 'php/curl',
                'content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->access_token
            ]);
        if ($method == 'POST') {
            $request->setFormat(\yii\httpclient\Client::FORMAT_JSON);
        }
        $response = $request->send();
//        pr($response);
//if($a){return $response;}
        $this->hasResponseError($response);
        if ($response->isOk ) {
            $decodedResponse = json_decode($response->content);
            if(!$this->hasResponseError($decodedResponse)){
                return $decodedResponse;
            }
        } else {
            $message = "Errore di connessione a Shutterstock";
            if (!empty($response->content)) {
                $decodeMessage = json_decode($response->content);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $message = $this->formatError($decodeMessage,$response->statusCode);
//                    $message = $decodeMessage->message;
//                    $message = json_encode($response->content);
                }
            }
            $this->errorMessage = $message;
//                throw new ShutterstockException($message);
        }
//        }
        return [];
    }

    public function formatError($error, $statusCode){
        $message = $error->message;
        if($statusCode == 429){
            $message = FileModule::t('amosattachments', "Hai superato il limite di chiamate disponibili ({remaining}/{limit}), riprova più tardi",[
                'limit' => $error->limit,
                'remaining' => $error->remaining,
            ]);
        }
        return $message;

    }

    /**
     * @param $decodedResponse
     * @return bool
     */
    public function hasResponseError($decodedResponse){
        if(!empty($decodedResponse->errors)){
            foreach ($decodedResponse->errors as $error){
                $this->errorMessage .= $error->message.' ';
            }
            return true;
        }
        return false;
    }

    /**
     *        $params = [
     * //            "query" => "kites",
     * //            "image_type" => "photo",
     * //            "page" => 1,
     * //            "per_page" => 5,
     * //            "sort" => "popular",
     * //            "view" => "minimal"
     * //        ];
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function searchImages($params)
    {
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $params['per_page'] = 20;
        $params['language'] = 'it';
        $params['region'] = 'it';
        $params['view'] = "minimal";
//        $params['aspect_ratio'] = "1.7";

//        $params['keyword_safe_search'] = 0;
//        $params['color'] = "grayscale";

        $content = $this->callApi('/images/search', 'GET', $params);
        return $content;

    }

    /**
     * @return array|mixed
     */
    public function searchImageCategories()
    {
        $params['language'] = 'it';
        $content = $this->callApi('/images/categories', 'GET', $params);
        return $content;
    }


    /**
     * @return array|mixed
     */
    public function searchImageSuggestion()
    {
        $params['language'] = 'it';
        $content = $this->callApi('/images/suggestions', 'GET', $params);
        return $content;
    }

    /**
     * @return array|mixed
     */
    public function searchImageDetail($id, $params = [])
    {
        $params['language'] = 'it';
        $content = $this->callApi('/images/' . $id, 'GET', $params);
        return $content;
    }

    /**
     * @param $params
     * @return array|mixed
     */
    public function getSubscriptions($params = [])
    {
        $content = $this->callApi('/user/subscriptions', 'GET', $params, true);
        return $content;
    }

    /**
     * @param $params
     * @return array|mixed
     */
    public function getProscriptions($params = [])
    {
//        $params['image_id'] ='';
        $content = $this->callApi('/images/proscriptions', 'GET', $params, true);
        return $content;
    }


    /**
     *         $params = [
     *            "images" => [
     *                [
     *                    "size" => 'huge',
     *                    "image_id" => "1246109254",
     *                    "subscription_id" => "s39782780",
     *                ]
     *            ]
     *        ];
     * @param $params
     * @return array|mixed
     */
    public function buyProscriptions($params = [])
    {

        $content = $this->callApi("/images/proscriptions", 'POST', $params, true);
        return $content;
    }

    /**
     * @param $proscription_id
     * @param $params
     * @return string
     */
    public function downloadImage($proscription_id, $params = [])
    {
//        $proscription_id = '062586c0-6175-4431-8290-e8503fda1dad';
//        $params = ['size' => 'huge'];
        $content = $this->callApi("/images/proscriptions/$proscription_id/downloads", 'POST', $params, true);
        if (!empty($content->url)) {
            return $content->url;
        }
        return '';
    }

    /**
     * @param $params
     * @return array|mixed
     */
    public function getSuggestionsImage($params = [])
    {
        $content = $this->callApi('/images/search/suggestions', 'GET', $params, true);
        return $content;
    }

    /**
     * @param $params
     * @return array|mixed
     */
    public function getContributor($id)
    {
        $content = $this->callApi("/contributors/$id", 'GET');
        return $content;
    }

    /**
     * @param $params
     * @return array|mixed
     */
    public function getContributors($ids = [])
    {
        $params = [];
        foreach ($ids as $id) {
            $params['id'][] = $id;
        }
        $content = $this->callApi("/contributors", 'GET', $params);
        return $content;
    }


    /**
     * @param $paginationConfig
     * @return \yii\data\Pagination
     */
    public function getPagination($paginationConfig)
    {
        $pagination = new \yii\data\Pagination();
        $pagination->pageParam = 'page';
        if (!empty($paginationConfig->total_count)) {
            $totalCount = $paginationConfig->total_count;
            $pagination->totalCount = $totalCount;
        }

        if (!empty($paginationConfig->per_page)) {
            $pageSize = $paginationConfig->per_page;
            $pagination->pageSize = $pageSize;
            $pagination->setPage($paginationConfig->page - 1);

        }
        return $pagination;
    }



}