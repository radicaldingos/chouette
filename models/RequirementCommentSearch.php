<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequirementComment;

/**
 * RequirementCommentSearch represents the model behind the search form about `app\models\RequirementComment`.
 */
class RequirementCommentSearch extends RequirementComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'requirement_id', 'user_id', 'date_creation'], 'integer'],
            [['comment'], 'safe'],
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
        $query = RequirementComment::find();

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
            'date_creation' => $this->date_creation,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param int $id
     *
     * @return ActiveDataProvider
     */
    public function searchByRequirementId($id)
    {
        $query = RequirementComment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'requirement_id' => $id,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
