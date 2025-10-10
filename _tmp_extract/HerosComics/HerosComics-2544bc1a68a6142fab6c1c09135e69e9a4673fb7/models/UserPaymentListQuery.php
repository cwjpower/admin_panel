<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserPaymentList]].
 *
 * @see UserPaymentList
 */
class UserPaymentListQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserPaymentList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserPaymentList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
