<?php

namespace vaultke\foundation\behaviors;

use Yii;

/**
 * This is the model class for table "tbl_audit".
 *
 * @property int $audit_id
 * @property string $audit_time
 * @property string $model_name
 * @property string $operation
 * @property string $field_name
 * @property string $old_value
 * @property string $new_value
 * @property string $user_id
 * @property string $ip_address
 */
class Audit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%audit_trail}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('logDb');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['audit_time', 'request_method', 'model_name', 'operation', 'field_name', 'old_value', 'new_value', 'user_id', 'ip_address'], 'required'],
            [['old_value', 'duration', 'memory_max', 'request_route', 'new_value'], 'string'],
            [['audit_time', 'model_name', 'operation', 'duration', 'memory_max', 'field_name', 'user_id', 'ip_address'], 'string', 'max' => 100],
        ];
    }
}