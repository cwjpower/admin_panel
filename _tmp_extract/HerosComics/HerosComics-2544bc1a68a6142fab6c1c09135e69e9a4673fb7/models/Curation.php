<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_curation".
 *
 * @property string $ID
 * @property string $curation_title
 * @property string $cover_img
 * @property int $curation_order
 * @property int $curation_status
 * @property int $curator_level User Level
 * @property string $curation_meta Book IDs
 * @property string $created_dt
 * @property string $user_id
 * @property int $hit_count
 *
 * @property Users $user
 */
class Curation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_curation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['curation_title', 'cover_img', 'curation_order', 'curation_status', 'curator_level', 'curation_meta', 'created_dt', 'user_id', 'hit_count'], 'required'],
            [['cover_img', 'curation_meta'], 'string'],
            [['curation_order', 'curation_status', 'curator_level', 'user_id', 'hit_count'], 'integer'],
            [['created_dt'], 'safe'],
            [['curation_title'], 'string', 'max' => 200],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'curation_title' => 'Curation Title',
            'cover_img' => 'Cover Img',
            'curation_order' => 'Curation Order',
            'curation_status' => 'Curation Status',
            'curator_level' => 'Curator Level',
            'curation_meta' => 'Curation Meta',
            'created_dt' => 'Created Dt',
            'user_id' => 'User ID',
            'hit_count' => 'Hit Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['ID' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return CurationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CurationQuery(get_called_class());
    }
}
