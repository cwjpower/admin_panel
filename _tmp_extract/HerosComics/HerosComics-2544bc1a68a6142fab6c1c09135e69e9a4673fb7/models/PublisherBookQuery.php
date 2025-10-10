<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PublisherBook]].
 *
 * @see PublisherBook
 */
class PublisherBookQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PublisherBook[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PublisherBook|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
