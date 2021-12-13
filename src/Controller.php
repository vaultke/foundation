<?php
namespace vaultke\foundation;
use Yii;
use yii\filters\Cors;
class Controller extends \yii\rest\Controller
{
   
    public $enableCsrfValidation = false;
    
    public function behaviors() {
        $auth     = isset(Yii::$app->params['activateAuth']) ? Yii::$app->params['activateAuth'] : FALSE;
        $origins  = isset(Yii::$app->params['allowedDomains']) ? Yii::$app->params['allowedDomains'] : "*";
        $behavior = [
            'class' => Cors::className(),
            'cors'  => [
                'Origin'                           => [$origins],
                'Access-Control-Allow-Origin'      => [$origins],  
                'Access-Control-Request-Headers'   => ['*'],         
                'Access-Control-Request-Method'    => ['POST', 'PUT', 'PATCH', 'GET', 'DELETE', 'HEAD'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age'           => 3600,                
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
     * error response
     */
    public function errorResponse($errors,$errorId=false,$message=false)
    {
        Yii::$app->response->statusCode = 422;
        if($errorId){
            foreach($errors as $key=>$value){
                $error[$id][]=[$value->getErrors()];
            }
            $errors= $error; 
        }
        foreach($errors as $key=>$value){
            $errors[$key]=$value[0];
        }
        $array['errorPayload']['errors']=$errors;

        if($message){
            $array['errorPayload'] = array_merge($array['errorPayload']['errors'], $this->toastResponse(
                [
                    'statusCode'=>422,
                    'message'=>$message ? $message : 'Some data could not be validated',
                    'theme'=>'danger'
                ]
            )['toastPayload']);

        }
        return $array;
    }
    /**
     * payload response
     */
    public function payloadResponse($data,$options=[])
    {
        $options = array_merge(['statusCode'=>200,'oneRecord'=>true, 'message'=>false], $options);
        Yii::$app->response->statusCode = $options['statusCode'];
        if(!$options['oneRecord']){
            $array = [ 
                'dataPayload'=> [
                    'data'              => $data->models,
                    'countOnPage'       => $data->count,
                    'totalCount'        => $data->totalCount,
                    'perPage'           => $data->pagination->pageSize,
                    'totalPages'        => $data->pagination->pageCount,
                    'currentPage'       => $data->pagination->page + 1,
                    'paginationLinks'   => $data->pagination->links,
                ]
            ];
        }else{
            $array = [ 
                'dataPayload'=> [
                    'data'              => $data,
                ]
            ];
            if($options['message']){
                $array = array_merge($array, $this->toastResponse(
                    [
                        'statusCode'=>$options['statusCode'],
                        'message'=>$options['message'],
                        'theme'=>'success'
                    ]
                ));
            }
            //$array['dataPayload'] = $model;
        }
        return $array;
    }
    /**
     * toast response
     */
    public function toastResponse($options=[])
    {
        $options = array_merge(['statusCode'=>200,'theme'=>false, 'message'=>false], $options);
        Yii::$app->response->statusCode = $options['statusCode'];
        $array = [ 
            'toastPayload'=> [
                'toastMessage'  => $options['message'] ? $options['message'] : 'Hello toast',
                'toastTheme'    => $options['theme'] ? $options['theme'] : 'info',
                'toastOptions'  => function($options){
                    if(isset($options['toastOptions'])){
                        return $options['toastOption'];
                    }else{
                        return [];
                    }
                }
            ]
        ];
        return $array;
    }
    /**
     * Query parameters cleanup
     */
    public function queryParameters($query,$modelId){   
        foreach($query as $key=>$value){
            if(substr($key,0,1) == '_'){
                $data[$modelId][ltrim($key,"_")]=$value;
            }else{
                $data[$key]=$value;
            }
        }
        return $data;
    }
}