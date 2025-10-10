<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_user_level_terms".
 *
 * @property int $ID
 * @property int $user_level
 * @property string $term_items
 * @property string $meta_value
 */
class UserLevelTerms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_user_level_terms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_level'], 'integer'],
            [['term_items', 'meta_value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_level' => 'User Level',
            'term_items' => 'Term Items',
            'meta_value' => 'Meta Value',
        ];
    }

    /**
     * @inheritdoc
     * @return UserLevelTermsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserLevelTermsQuery(get_called_class());
    }
}
