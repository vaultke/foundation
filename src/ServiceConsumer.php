<?php
namespace vaultke\foundation;

use Exception;
use yii\httpclient\Client;
use yii\web\Response;
use yii\helpers\Url;
trait ServiceConsumer
{
    /**
     * Send a request to any service
     */
    public function performRequest($method, $requestUrl, $requestBody = [], $headers = [], $query = [])
    {
        try {
            $client = new Client(['base_uri' => Url::base(true)]);

            $headers['Authorization'] = Yii::$app->request->headers['authorization'];

            $response = $client->request($method, $requestUrl, ['json' => $requestBody, /*'headers' => $headers,*/ 'query' => $query]);

            $status = $response->getStatusCode();

            if ( $status == Response::HTTP_OK || $status == Response::HTTP_CREATED ){
                return json_decode((string) $response->getBody());
            } else {
                return null;
            }
        } catch(Exception $ex){
            return null;
        }
    }
}