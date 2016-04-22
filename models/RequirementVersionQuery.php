<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[RequirementVersion]].
 *
 * @see RequirementVersion
 */
class RequirementVersionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return RequirementVersion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RequirementVersion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
