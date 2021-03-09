<?php
namespace vaultke\foundation;

use yii\behaviors\TimestampBehavior;
class ActiveRecord extends \yii\db\ActiveRecord
{
	public function behaviors()
	{
	    return [
	        TimestampBehavior::className()
	    ];
	}

}