<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Section;

/**
 * SectionSearch represents the model behind the search form about `app\models\Section`.
 */
class SectionSearch extends Section
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'created', 'status', 'priority_id', 'project_id', 'tree', 'lft', 'rgt', 'depth', 'icon_type'], 'integer'],
            [['reference', 'name', 'icon', 'type'], 'safe'],
            [['active', 'selected', 'disabled', 'readonly', 'visible', 'collapsed', 'movable_u', 'movable_d', 'movable_l', 'movable_r', 'removable', 'removable_all'], 'boolean'],
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
        $query = Section::find();

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
            'category_id' => $this->category_id,
            'created' => $this->created,
            'priority_id' => $this->priority_id,
            'project_id' => $this->project_id,
            'tree' => $this->tree,
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'depth' => $this->depth,
            'active' => $this->active,
            'selected' => $this->selected,
            'disabled' => $this->disabled,
            'readonly' => $this->readonly,
            'visible' => $this->visible,
            'collapsed' => $this->collapsed,
            'movable_u' => $this->movable_u,
            'movable_d' => $this->movable_d,
            'movable_l' => $this->movable_l,
            'movable_r' => $this->movable_r,
            'removable' => $this->removable,
            'removable_all' => $this->removable_all,
            'icon_type' => $this->icon_type,
        ]);

        $query->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
