<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_terms".
 *
 * @property string $term_id
 * @property string $name
 * @property string $slug
 * @property string $term_group
 *
 * @property TermTaxonomy[] $termTaxonomies
 */
class Terms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_terms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'term_group'], 'required'],
            [['term_group'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'term_id' => 'Term ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'term_group' => 'Term Group',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermTaxonomies()
    {
        return $this->hasMany(TermTaxonomy::className(), ['term_id' => 'term_id']);
    }

    /**
     * @inheritdoc
     * @return TermsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TermsQuery(get_called_class());
    }
}
