<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersMeta]].
 *
 * @see UsersMeta
 */
class UsersMetaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UsersMeta[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersMeta|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
