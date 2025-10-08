<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Books]].
 *
 * @see Books
 */
class BooksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Books[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Books|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
