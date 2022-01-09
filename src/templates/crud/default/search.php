<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();
$filterConditions = $generator->generateFilterConditions();


echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form of `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{ 

    public $search;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
            [['search'], 'safe'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->dataModel();

        $dataProvider = $this->dataProvider($query);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        <?= implode("\n        ", $searchConditions) ?>
        
        return $dataProvider;
    }
    public function filter($params)
    {
        $query = $this->dataModel();

        $dataProvider = $this->dataProvider($query);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        <?= implode("\n        ", $filterConditions) ?>

        return $dataProvider;
    }

    /**
     * Load all the data in one method
     *
     *
     * @return Model
     */
    protected function dataModel()
    {
       return  <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();
    }

    /**
     * Unified dataprovider method
     *
     * @return ActiveDataProvider
     */
    protected function dataProvider($query)
    {
        // add conditions that should always apply here

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 25 ],
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

    }
}
