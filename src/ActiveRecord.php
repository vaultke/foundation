<?php
namespace vaultke\foundation;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use vaultke\foundation\behaviors\Delete;
class ActiveRecord extends \yii\db\ActiveRecord
{
	public $cryptKey = '';
	
	public function behaviors()
	{
	    return [
	        TimestampBehavior::className(),
			\vaultke\foundation\behaviors\Delete::className(),
			\vaultke\foundation\behaviors\AuditTrail::className(),
			[
				'class' => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => $this->cryptKey.'_crypt_id',
				],
				'value' => function ($event) {
					return hash_hmac('sha256', md5(date('DYM').substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 10)), sha1(date('myd').rand(0,9999999999)));
				},
			],
	    ];
	}


}