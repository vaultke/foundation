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
    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        // add CORS filter
        $behaviors['corsFilter'] = [
                'class' => Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'      => '*',
                    'Access-Control-Request-Method'    => ['POST', 'PUT', 'GET', 'DELETE', 'HEAD'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                    'Access-Control-Allow-Headers'     => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization', 'x-service']
                ],
            ];
        // re-add authentication filter
        // $behaviors['authenticator'] = [
        //     'class' => HttpBearerAuth::className(),
        // ];
        //avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        //$behaviors['authenticator']['except'] = ['OPTIONS','login'];

        return $behaviors;
    }
    /**
     * Api Validate error response
     */
    public function apiValidate($errors,$acid=null)
    {
        Yii::$app->response->statusCode = 422;
        return [
            'errors' => $errors
        ];
    }
    public function apiValidateMultiple($errors,$id)
    {
        Yii::$app->response->statusCode = 422;
            foreach($errors as $key=>$value){
                $error[$id][]=[$value->getErrors()];
            }        
        return [
            'errors' => $error
        ];
    }
    /**
     * Api Item response
     */
    public function apiItem($data)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'data' => $data
        ];
    }

    /**
     * Api Collection response
     */
    public function apiCollection($data)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'data'          => $data->models,
            'countOnPage'   => $data->count,
            'totalCount'    => $data->totalCount,
            'pageSize'      => $data->pagination->pageSize,
            'pageCount'     => $data->pagination->pageCount,
        ];
    }

    /**
     * Api Toast response
     */
    public function apiToast($statusCode,$message = false)
    {
        Yii::$app->response->statusCode = $statusCode;
        return [
            'toast' => [
                        'message'=>$message ? $message : \vaultke\helpers\Status::getCode($statusCode)['message'],
                        'theme' => \vaultke\helpers\Status::getCode($statusCode)['theme']
                    ]
                        
        ];
    }

    /**
     * Query parameters
     */
    public function apiParams($query,$modelId)
    {   foreach($query as $key=>$value){
            if(substr($key,0,2)=='qp'){
                $data[$modelId][ltrim($key,"qp")]=$value;
            }else{
                $data[$key]=$value;
            }
        }
        return $data;
    }
}
