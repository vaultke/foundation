<?php
namespace vaultke\foundation\auth;

use Yii;
class AuthManager extends \yii\rbac\DbManager
{
    /**
     * @var string the name of the table storing authorization items. Defaults to "auth_item".
     */
    public $itemTable = '{{%item}}';
    /**
     * @var string the name of the table storing authorization item hierarchy. Defaults to "item_child".
     */
    public $itemChildTable = '{{%item_child}}';
    /**
     * @var string the name of the table storing authorization item assignments. Defaults to "assignment".
     */
    public $assignmentTable = '{{%assignment}}';
    /**
     * @var string the name of the table storing rules. Defaults to "rule".
     */
    public $ruleTable = '{{%rule}}'; 
    use AuthJwt;
    protected $checkAccessAssignments = [];

    public function loadPermissions($user_id, $permissions=null){
        $assigned = array_merge($this->getDirectPermissionsByUser($user_id),$this->getInheritedPermissionsByUser($user_id));
        foreach($assigned as $index => $row){
            $permissions .='"'.$index.'",';
        }
 
        return $this->encrypt(rtrim($permissions,","));
    }
    public function checkAccess($userId, $permissionName,$params = [])
    {
        if(isset(Yii::$app->params['deactivateRbac']) && Yii::$app->params['deactivateRbac']){
            return true;
        }
        if(substr($permissionName,-12)=="view-deleted"){
            $error = false;
        }else{
            $error = true;
        }
        if (isset($this->checkAccessAssignments[(string) $userId])) {
            $assignments = $this->checkAccessAssignments[(string) $userId];
        } else {
            $assignments =$this->_assignments();
            $this->checkAccessAssignments[(string) $userId] = $assignments;
        }

        if (empty($assignments)) {
            return $this->forbidden($error);
        }

        if (isset($assignments[$permissionName])) {
            return true;
        }

        return $this->forbidden($error);        
    }
    protected function forbidden($error){
        if($error){
            return throw new \yii\web\ForbiddenHttpException('You are not authorized to access this resource.');
        }else{
            return false;
        }
    }
    protected function _assignments(){
        $rbacKey = Yii::$app->user->identity->rbac;
        $data = $this->decrypt($rbacKey);
        $data = str_replace('"','',$data);
        $array = [];
        foreach(explode(",",$data) as $key){
            $array[$key] = Yii::$app->user->identity->user_id;
        }
        //var_dump($array);
        return $array;
    }
}