<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AppMobileAuth]].
 *
 * @see AppMobileAuth
 */
class AppMobileAuthQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AppMobileAuth[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppMobileAuth|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
