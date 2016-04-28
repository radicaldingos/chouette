<?php

namespace app\models;

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
            [['id', 'created', 'section_id'], 'integer'],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'section_id' => $this->section_id,
        ]);
        
        $query->innerJoin('section', 'requirement.section_id = section.id');
        $query->innerJoin('document', 'section.document_id = document.id');
        $query->orderBy([
            'document.id' => SORT_ASC,
            'section.id' => SORT_ASC,
            'status' => SORT_ASC,
        ]);

        return $dataProvider;
    }
    
    public function searchByCriteria($q)
    {
        $query = Requirement::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->leftJoin('requirement_version', 'requirement.id = requirement_version.requirement_id')
            ->andWhere(['LIKE', 'LOWER(requirement_version.statement)', strtolower($q)])
            ->orderBy('updated DESC, id DESC');

        return $dataProvider;
    }
}
