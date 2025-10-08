<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_banner".
 *
 * @property string $ID
 * @property string $bnr_title
 * @property string $bnr_url
 * @property string $bnr_target
 * @property string $hide_or_show
 * @property string $bnr_file_path
 * @property string $bnr_file_url
 * @property string $bnr_file_name
 * @property string $bnr_created
 * @property string $bnr_order 정렬순서 
 * @property string $bnr_from
 * @property string $bnr_to
 * @property string $user_id
 *
 * @property Users $user
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bnr_title', 'bnr_url', 'bnr_target', 'hide_or_show', 'bnr_file_path', 'bnr_file_url', 'bnr_file_name', 'bnr_created', 'bnr_order', 'user_id'], 'required'],
            [['bnr_url'], 'string'],
            [['bnr_created', 'bnr_from', 'bnr_to'], 'safe'],
            [['bnr_order', 'user_id'], 'integer'],
            [['bnr_title', 'bnr_file_name'], 'string', 'max' => 100],
            [['bnr_target', 'hide_or_show'], 'string', 'max' => 10],
            [['bnr_file_path', 'bnr_file_url'], 'string', 'max' => 250],
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
            'bnr_title' => 'Bnr Title',
            'bnr_url' => 'Bnr Url',
            'bnr_target' => 'Bnr Target',
            'hide_or_show' => 'Hide Or Show',
            'bnr_file_path' => 'Bnr File Path',
            'bnr_file_url' => 'Bnr File Url',
            'bnr_file_name' => 'Bnr File Name',
            'bnr_created' => 'Bnr Created',
            'bnr_order' => 'Bnr Order',
            'bnr_from' => 'Bnr From',
            'bnr_to' => 'Bnr To',
            'user_id' => 'User ID',
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
     * @return BannerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BannerQuery(get_called_class());
    }
}
