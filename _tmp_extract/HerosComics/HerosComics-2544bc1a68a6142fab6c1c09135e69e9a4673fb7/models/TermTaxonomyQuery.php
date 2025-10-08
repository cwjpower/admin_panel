<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TermTaxonomy]].
 *
 * @see TermTaxonomy
 */
class TermTaxonomyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TermTaxonomy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TermTaxonomy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
