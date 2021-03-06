<?php
//changed namespace from machapisho\api
namespace vaultke\foundation;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
class Controller extends \yii\rest\Controller
{
    use TraitController;
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public static function allowedDomains() {
        // '*',                        // star allows all domains
        $domains[]=$_SERVER["REMOTE_ADDR"];
        foreach (explode(",",getenv('ALLOWED_DOMAINS')) as $domain) {
            $domains[] = rtrim(ltrim($domain,' '),' ');
        }
        return $domains;
    }
    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        // add CORS filter
        $behaviors['corsFilter'] = [
                'class' => Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'      => '*',//static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST', 'PUT', 'GET', 'DELETE', 'HEAD'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                    'Access-Control-Allow-Headers'     => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization']
                ],
            ];
        // re-add authentication filter
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        //avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['OPTIONS','login'];

        return $behaviors;
    }
    /**
     * Api Validate error response
     */
    public function apiValidate($errors, $message = false)
    {
        Yii::$app->response->statusCode = 422;
        return [
            'status' => 422,
            //'name' => 'ValidateErrorException',
            //'message' => $message ? $message : 'Error validation',
            'errors' => $errors
        ];
    }

    /**
     * Api Created response
     */
    public function apiCreated($data, $message = false)
    {
        Yii::$app->response->statusCode = 201;
        return [
            'statusCode' => 201,
            'message' => $message ? $message : 'Created successfully',
            'data' => $data
        ];
    }

    /**
     * Api Updated response
     */
    public function apiUpdated($data, $message = false)
    {
        Yii::$app->response->statusCode = 202;
        return [
            'statusCode' => 202,
            'message' => $message ? $message : 'Updated successfully',
            'data' => $data
        ];
    }

    /**
     * Api Deleted response
     */
    public function apiDeleted($data, $message = false)
    {
        Yii::$app->response->statusCode = 202;
        return [
            'statusCode' => 202,
            'message' => $message ? $message : 'Deleted successfully',
            'data' => $data
        ];
    }

    /**
     * Api Item response
     */
    public function apiItem($data, $message = false)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'statusCode' => 200,
            'message' => $message ? $message : 'Data retrieval successful',
            'data' => $data
        ];
    }

    /**
     * Api Collection response
     */
    public function apiCollection($data, $total = 0, $message = false)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'statusCode' => 200,
            'message' => $message ? $message : 'Data retrieval successful',
            'data' => $data,
            'total' => $total
        ];
    }

    /**
     * Api Success response
     */
    public function apiSuccess($message = false)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'statusCode' => 200,
            'message' => $message ? $message : 'Success',
        ];
    }
}
