<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
    <?php if (!empty($generator->searchModelClass)): ?>
    if(Yii::$app->user->can('<?=strtolower($_ENV['APP_CONTEXT'].'_'.$modelClass.'_list')?>')){
    <?php $smodel = isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>
        $searchModel = new <?= $smodel ?>();
            $search = $this->queryParameters(Yii::$app->request->queryParams,'<?= $smodel ?>');
            $dataProvider = $searchModel->search($search);
            if(isset($search['<?= $smodel?>']['advance'])){
                $dataProvider = $searchModel->filter($search);
            }
            return $this->payloadResponse($dataProvider,['oneRecord'=>false]);
        }
    <?php else: ?>
            $dataProvider = new ActiveDataProvider([
                'query' => <?= $modelClass ?>::find(),
                /*
                'pagination' => [
                    'pageSize' => 50
                ],
                'sort' => [
                    'defaultOrder' => [
    <?php foreach ($pks as $pk): ?>
                        <?= "'$pk' => SORT_DESC,\n" ?>
    <?php endforeach; ?>
                    ]
                ],
                */
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
    <?php endif; ?>
}

    /**
     * Displays a single <?= $modelClass ?> model.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->user->can('<?=strtolower($_ENV['APP_CONTEXT'].'_'.$modelClass.'_view')?>')){
            $model=$this->findModel($id);
            return $this->payloadResponse($model);
        }
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('<?=strtolower($_ENV['APP_CONTEXT'].'_'.$modelClass.'_create')?>')){
            $model = new <?= $modelClass ?>();
            $dataRequest['<?= $modelClass ?>'] = Yii::$app->request->getBodyParams();
            if($model->load($dataRequest) && $model->save()) {
                return $this->payloadResponse($model,['statusCode'=>201,'message'=>'<?= $modelClass ?> added successfully']);
            }
            return $this->errorResponse($model->getErrors()); 
        }
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('<?=strtolower($_ENV['APP_CONTEXT'].'_'.$modelClass.'_update')?>')){
            $dataRequest['<?= $modelClass ?>'] = Yii::$app->request->getBodyParams();
            $model = $this->findModel($id);
            if($model->load($dataRequest) && $model->save()) {
                return $this->payloadResponse($model,['statusCode'=>202,'message'=>'<?= $modelClass ?> updated successfully']);
            }
            return $this->errorResponse($model->getErrors()); 
        }
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the record will be marked is_deleted = TRUE.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('<?=strtolower($_ENV['APP_CONTEXT'].'_'.$modelClass.'_delete')?>')){
            $model = $this->findModel($id);
            if($model->delete()){
                return $this->toastResponse(['statusCode'=>202,'message'=>'<?= $modelClass ?> deleted successfully']);
            };
            return $this->errorResponse($model->getErrors()); 
        }
    }

    /**
     * Restores an existing <?= $modelClass ?> model.
     * If restore is successful, the record will be marked is_deleted = FALSE.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRestore($id)
    {
        if(Yii::$app->user->can('<?=strtolower($_ENV['APP_CONTEXT'].'_'.$modelClass.'_restore')?>')){
            $model = $this->findModel($id);
            if($model->restore()){
                return $this->toastResponse(['statusCode'=>202,'message'=>'<?= $modelClass ?> restored successfully']);
            };
            return $this->errorResponse($model->getErrors()); 
        }
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
<?php
if (count($pks) === 1) {
    $condition = '["'.strtolower($modelClass).'_crypt_id"=>$id]';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(<?= $generator->generateString('The requested record does not exist.') ?>);
    }
}
