<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequirementVersion;

/**
 * RequirementVersionSearch represents the model behind the search form about `app\models\RequirementVersion`.
 */
class RequirementVersionSearch extends RequirementVersion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'requirement_id', 'updated', 'status'], 'integer'],
            [['title', 'version', 'wording', 'justification'], 'safe'],
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
        $query = RequirementVersion::find();

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
            'requirement_id' => $this->requirement_id,
            'updated' => $this->updated,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'wording', $this->wording]);

        return $dataProvider;
    }
}
