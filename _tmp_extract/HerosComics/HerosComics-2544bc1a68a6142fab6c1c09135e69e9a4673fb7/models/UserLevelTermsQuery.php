<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserLevelTerms]].
 *
 * @see UserLevelTerms
 */
class UserLevelTermsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserLevelTerms[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserLevelTerms|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
