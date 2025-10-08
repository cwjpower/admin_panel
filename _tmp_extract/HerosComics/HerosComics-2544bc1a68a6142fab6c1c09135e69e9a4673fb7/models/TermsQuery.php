<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Terms]].
 *
 * @see Terms
 */
class TermsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Terms[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Terms|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
