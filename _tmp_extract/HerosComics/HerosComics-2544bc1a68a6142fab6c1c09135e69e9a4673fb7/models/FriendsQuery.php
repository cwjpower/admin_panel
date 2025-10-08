<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Friends]].
 *
 * @see Friends
 */
class FriendsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Friends[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Friends|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
