<?php
    
    namespace vaultke\foundation\behaviors;
    
    use Yii;
    use yii\base\Behavior;
    use yii\base\Exception;
    use yii\db\ActiveRecord;
    use yii\db\Expression;
    
    /**
     * Class AuditTrailBehaviour
     *
     */
    class AuditTrail extends Behavior
    {
        /**
         * string
         */
        const NO_USER_ID = "NO_USER_ID";
        
        /**
         * @param $class
         * @param $attribute
         *
         * @return string
         */
        public static function getLabel($class, $attribute)
        {
            $labels = $class::attributeLabels();
            if (isset($labels[$attribute])) {
                return $labels[$attribute];
            } else {
                return ucwords(str_replace('_', ' ', $attribute));
            }
        }
        
        /**
         * @return array
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
                ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ];
        }
        
        /**
         * @param      $event
         *
         * @param null $attributes
         *
         * @return mixed
         */
        public function afterSave($event, $attributes = null)
        {
            $app = Yii::$app;
            $request = $app->request;
            if(Yii::$app->user->identity != null){
                $userId = Yii::$app->user->identity->user_id;
            }else{
                $userId = self::NO_USER_ID;
            }
            $newAttributes = $this->owner->getAttributes();
            $oldAttributes = $event->changedAttributes;
            
            $action = Yii::$app->controller->action->id;
            
            if (!$this->owner->isNewRecord) {
                // compare old and new
                foreach ($oldAttributes as $name => $oldValue) {
                    if (!empty($newAttributes)) {
                        $newValue = $newAttributes[$name];
                    } else {
                        $newValue = 'NA';
                    }
                    if ($oldValue != $newValue && $name != 'updated_at') {
                        $log = new Audit();
                        $log->old_value = $oldValue;
                        $log->new_value = $newValue;
                        $log->operation = $name == 'is_deleted' ? 'DELETE' : 'UPDATE';
                        $log->model_name = substr(get_class($this->owner), strrpos(get_class($this->owner), '\\') + 1);
                        $log->field_name = $name;
                        $log->audit_time = new Expression('unix_timestamp(NOW())');
                        $log->user_id = $userId;
                        $log->ip_address = Yii::$app->request->getUserIP();
                        $log->request_method = $request->method;
                        $log->duration = ( microtime(true) - YII_BEGIN_TIME);
                        $log->memory_max = memory_get_peak_usage();
                        $log->request_route = $app->requestedAction ? $app->requestedAction->uniqueId : null;
                        $log->save(false);
                    }
                }
            } else {
                foreach ($newAttributes as $name => $value) {
                    if ($name != 'created_at' || $name != 'updated_at') {
                        $log = new Audit();
                        $log->old_value = 'NA';
                        $log->new_value = $value;
                        $log->operation = 'INSERT';
                        $log->model_name = substr(get_class($this->owner), strrpos(get_class($this->owner), '\\') + 1);
                        $log->field_name = $name;
                        $log->audit_time = new Expression('unix_timestamp(NOW())');
                        $log->user_id = $userId;
                        $log->ip_address = Yii::$app->request->getUserIP();
                        $log->request_method = $request->method;
                        $log->duration = ( microtime(true) - YII_BEGIN_TIME);
                        $log->memory_max = memory_get_peak_usage();
                        $log->request_route = $app->requestedAction ? $app->requestedAction->uniqueId : null;
                        $log->save();
                    }
                }
            }
            return true;
        }
        
    }