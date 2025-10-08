<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MemoLogs]].
 *
 * @see MemoLogs
 */
class MemoLogsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MemoLogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MemoLogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
