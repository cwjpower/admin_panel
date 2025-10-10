<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_promotion".
 *
 * @property string $ID
 * @property string $prom_title
 * @property string $prom_content
 * @property string $prom_type
 * @property string $user_count 수신자 수 
 * @property string $created_dt
 * @property string $user_id
 */
class Promotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_promotion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prom_title', 'prom_content', 'prom_type', 'user_count', 'created_dt', 'user_id'], 'required'],
            [['prom_content'], 'string'],
            [['created_dt'], 'safe'],
            [['user_id'], 'integer'],
            [['prom_title'], 'string', 'max' => 100],
            [['prom_type'], 'string', 'max' => 20],
            [['user_count'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'prom_title' => 'Prom Title',
            'prom_content' => 'Prom Content',
            'prom_type' => 'Prom Type',
            'user_count' => 'User Count',
            'created_dt' => 'Created Dt',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @inheritdoc
     * @return PromotionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PromotionQuery(get_called_class());
    }
}
