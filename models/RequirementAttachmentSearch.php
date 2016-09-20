<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequirementAttachment;

/**
 * RequirementAttachmentSearch represents the model behind the search form about `app\models\RequirementAttachment`.
 */
class RequirementAttachmentSearch extends RequirementAttachment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'requirement_id'], 'integer'],
            [['path'], 'string'],
            [['name'], 'string', 'max' => 40],
            [['name', 'path'], 'safe'],
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
        $query = RequirementAttachment::find();

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
        ]);
        
        return $dataProvider;
    }
}
