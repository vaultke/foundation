<?php
namespace vaultke\foundation;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use vaultke\foundation\behaviors\SoftDeleteBehavior;
class ActiveRecord extends \yii\db\ActiveRecord
{
	use Status;
	public $cryptKey = '';
	
	public function behaviors()
	{
	    return [
	        TimestampBehavior::className(),
			SoftDeleteBehavior::className(),
			\vaultke\foundation\behaviors\AuditTrail::className(),
			[
				'class' => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => $this->cryptKey.'_crypt_id',
				],
				'value' => function ($event) {
					return Helpers::cipherKey();
				},
			],
	    ];
	}


}