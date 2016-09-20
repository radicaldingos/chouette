<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequirementLog;

/**
 * RequirementLogSearch represents the model behind the search form about `app\models\RequirementLog`.
 */
class RequirementLogSearch extends RequirementLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requirement_id', 'user_id', 'date'], 'integer'],
            [['event'], 'string', 'max' => 20],
            [['event'], 'safe'],
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
        $query = RequirementLog::find();

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
            'requirement_id' => $this->requirement_id,
            'user_id' => $this->user_id,
            'date' => $this->date,
            'event' => $this->event,
        ]);
        
        return $dataProvider;
    }
}
