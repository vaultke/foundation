<?php
$model = \yii\helpers\StringHelper::basename($generator->modelClass);
echo "<?php\n";
?>
/**
 * @OA\Tag(
 *     name="<?=$model?>",
 *     description="Available endpoints for <?=$model?> model"
 * )
 */