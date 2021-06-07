<?php
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
        $auth = isset(Yii::$app->params['activateAuth']) ? Yii::$app->params['activateAuth'] : FALSE;
        $behavior = [
            'class' => Cors::className(),
            'cors'  => [
                // restrict access to domains:
                'Origin'                           => ['*'],
                'Access-Control-Allow-Origin'      => ['*'],                
                'Access-Control-Request-Method'    => ['POST', 'PUT', 'PATCH', 'GET', 'DELETE', 'HEAD'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age'           => 3600,                
                'Access-Control-Allow-Headers'     => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization', 'x-service']
                
            ],
        ]; 
        if($auth){
            $behaviors = parent::behaviors();
            unset($behaviors['authenticator']);
            $behaviors['corsFilter']=$behavior;
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
            ];
            $behaviors['authenticator']['except'] = ['OPTIONS','register','login','error','request-password-reset','reset-password'];
        }else{
            $behaviors['corsFilter']=$behavior;
        }
        
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
            'payload' => $data
        ];
    }

    /**
     * Api Collection response
     */
    public function apiCollection($data)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'payload'          => $data->models,
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
