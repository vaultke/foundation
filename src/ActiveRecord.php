<?php
namespace vaultke\foundation;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use vaultke\foundation\behaviors\SoftDeleteBehavior;
use vaultke\foundation\behaviors\AuditTrail;
class ActiveRecord extends \yii\db\ActiveRecord
{
	use Status;
	public $cryptKey = 'crypt_id';
	public $UID = '';
	
	public function behaviors()
	{
	    return [
	        TimestampBehavior::className(),
			SoftDeleteBehavior::className(),
			AuditTrail::className(),
			[
				'class' => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => $this->addCryptId(),
				],
				'value' => function ($event) {
					return Helpers::cipherKey();
				},
			],
	    ];
	}
	public function addCryptId()
	{
	  $attribute = $this->cryptKey;
	  $uid = $this->UID;
	  $modelName = strtolower(substr(get_class($this), strrpos(get_class($this), '\\') + 1));
	  if($this->hasAttribute($uid)){
		return $uid;
	  }else{
		if ($this->hasAttribute($attribute)) {
			return $attribute;
		  }elseif($this->hasAttribute($modelName.'_'.$attribute)){
			return $modelName.'_'.$attribute;
		}
	  }
	}


}