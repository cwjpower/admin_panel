<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PostsQnas]].
 *
 * @see PostsQnas
 */
class PostsQnasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PostsQnas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostsQnas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
