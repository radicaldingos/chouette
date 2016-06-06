<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Requirement;

/**
 * RequirementSearch represents the model behind the search form about `app\models\Requirement`.
 */
class RequirementSearch extends Requirement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Requirement::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'type' => Requirement::TYPE,
        ]);

        return $dataProvider;
    }
    
    /**
     * Search for requirement matching with a criteria
     * 
     * The search is made on every requirements versions, on title, wording and
     * justification.
     * 
     * @param type $q The search criteria
     * 
     * @return ActiveDataProvider
     */
    public function searchByCriteria($q)
    {
        $query = Requirement::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->leftJoin('requirement_version', 'item.id = requirement_version.requirement_id')
            ->andWhere(['LIKE', 'LOWER(requirement_version.title)', strtolower($q)])
            ->orWhere(['LIKE', 'LOWER(requirement_version.wording)', strtolower($q)])
            ->orWhere(['LIKE', 'LOWER(requirement_version.justification)', strtolower($q)])
            ->andWhere("project_id = " . Yii::$app->session->get('user.current_project')->id)
            ->orderBy('updated DESC, id DESC');

        return $dataProvider;
    }
}
