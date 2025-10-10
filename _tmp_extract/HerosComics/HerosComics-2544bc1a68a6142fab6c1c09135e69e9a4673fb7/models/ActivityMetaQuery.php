<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ActivityMeta]].
 *
 * @see ActivityMeta
 */
class ActivityMetaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ActivityMeta[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ActivityMeta|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
