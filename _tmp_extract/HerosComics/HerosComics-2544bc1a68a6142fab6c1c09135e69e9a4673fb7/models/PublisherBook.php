<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_publisher_book".
 *
 * @property string $ID
 * @property string $publisher_id
 * @property string $period_from
 * @property string $period_to
 * @property int $publisher_order
 * @property string $created_dt
 * @property string $publisher_ci
 *
 * @property Users $publisher
 */
class PublisherBook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_publisher_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['publisher_id', 'period_from', 'period_to', 'publisher_order', 'created_dt'], 'required'],
            [['publisher_id', 'publisher_order'], 'integer'],
            [['period_from', 'period_to', 'created_dt'], 'safe'],
            [['publisher_ci'], 'string'],
            [['publisher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['publisher_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'publisher_id' => 'Publisher ID',
            'period_from' => 'Period From',
            'period_to' => 'Period To',
            'publisher_order' => 'Publisher Order',
            'created_dt' => 'Created Dt',
            'publisher_ci' => 'Publisher Ci',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublisher()
    {
        return $this->hasOne(Users::className(), ['ID' => 'publisher_id']);
    }

    /**
     * @inheritdoc
     * @return PublisherBookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PublisherBookQuery(get_called_class());
    }
}
