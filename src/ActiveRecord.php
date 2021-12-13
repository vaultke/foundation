<?php
namespace vaultke\foundation;

use yii\behaviors\TimestampBehavior;
use vaultke\foundation\behaviors\Delete;
class ActiveRecord extends \yii\db\ActiveRecord
{
	public function behaviors()
	{
	    return [
	        TimestampBehavior::className(),
			Delete::className(),
			\vaultke\foundation\behaviors\AuditTrail::className(),
	    ];
	}

}