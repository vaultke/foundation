<?php
namespace vaultke\foundation\auth;

use Firebase\JWT\JWT;

class AuthIdentity extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    use AuthJwt;
    public $user_id;
    public $username;
    public $username_alias;
    public $auth_key;
    public $rbac;
    public $created_at;
    public $updated_at;

    public static function findIdentity($id){
        return [];
    }
    public function getId(){
        return $this->user_id;
    }
    public function getAuthKey(){
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
}
