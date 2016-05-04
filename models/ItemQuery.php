<?php

namespace app\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * This is the ActiveQuery class for [[Item]].
 *
 * @see Item
 */
class ItemQuery extends \yii\db\ActiveQuery
{
    public $type;
    
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
    
    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }

    /**
     * @inheritdoc
     * @return Item[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Item|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
