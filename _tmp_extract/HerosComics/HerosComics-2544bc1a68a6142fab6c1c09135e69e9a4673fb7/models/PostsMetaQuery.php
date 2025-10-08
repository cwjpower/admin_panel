<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PostsMeta]].
 *
 * @see PostsMeta
 */
class PostsMetaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PostsMeta[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostsMeta|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
