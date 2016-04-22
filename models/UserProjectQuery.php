<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserProject]].
 *
 * @see UserProject
 */
class UserProjectQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserProject[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserProject|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
