<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[BannedUsers]].
 *
 * @see BannedUsers
 */
class BannedUsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BannedUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BannedUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
