<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_user_cash_logs".
 *
 * @property string $ID
 * @property string $user_id
 * @property int $cash_used
 * @property int $cash_total
 * @property string $cash_comment 상세설명
 * @property int $point_used
 * @property int $point_total
 * @property string $created_dt
 *
 * @property Users $user
 */
class UserCashLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_user_cash_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cash_used', 'cash_total', 'cash_comment', 'point_used', 'point_total', 'created_dt'], 'required'],
            [['user_id', 'cash_used', 'cash_total', 'point_used', 'point_total'], 'integer'],
            [['cash_comment'], 'string'],
            [['created_dt'], 'safe'],
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
            'user_id' => 'User ID',
            'cash_used' => 'Cash Used',
            'cash_total' => 'Cash Total',
            'cash_comment' => 'Cash Comment',
            'point_used' => 'Point Used',
            'point_total' => 'Point Total',
            'created_dt' => 'Created Dt',
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
     * @return UserCashLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserCashLogsQuery(get_called_class());
    }
}
