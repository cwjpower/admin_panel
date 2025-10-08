<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_term_taxonomy".
 *
 * @property string $term_taxonomy_id
 * @property string $term_id
 * @property string $taxonomy
 * @property string $description
 * @property string $parent
 * @property int $count
 *
 * @property Terms $term
 */
class TermTaxonomy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_term_taxonomy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term_id', 'taxonomy', 'description', 'parent', 'count'], 'required'],
            [['term_id', 'parent', 'count'], 'integer'],
            [['description'], 'string'],
            [['taxonomy'], 'string', 'max' => 32],
            [['term_id'], 'exist', 'skipOnError' => true, 'targetClass' => Terms::className(), 'targetAttribute' => ['term_id' => 'term_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'term_taxonomy_id' => 'Term Taxonomy ID',
            'term_id' => 'Term ID',
            'taxonomy' => 'Taxonomy',
            'description' => 'Description',
            'parent' => 'Parent',
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerm()
    {
        return $this->hasOne(Terms::className(), ['term_id' => 'term_id']);
    }

    /**
     * @inheritdoc
     * @return TermTaxonomyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TermTaxonomyQuery(get_called_class());
    }
}
