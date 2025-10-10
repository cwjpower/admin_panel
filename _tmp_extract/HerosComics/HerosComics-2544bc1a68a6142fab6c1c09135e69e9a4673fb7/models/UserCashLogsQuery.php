<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserCashLogs]].
 *
 * @see UserCashLogs
 */
class UserCashLogsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserCashLogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserCashLogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
