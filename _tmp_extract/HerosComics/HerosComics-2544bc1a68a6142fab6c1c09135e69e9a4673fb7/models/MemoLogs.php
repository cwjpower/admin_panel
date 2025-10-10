<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_memo_logs".
 *
 * @property string $ID
 * @property string $user_id
 * @property string $memo
 * @property string $created_by
 * @property string $created_dt
 *
 * @property Users $user
 */
class MemoLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_memo_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'memo', 'created_by', 'created_dt'], 'required'],
            [['user_id'], 'integer'],
            [['memo'], 'string'],
            [['created_dt'], 'safe'],
            [['created_by'], 'string', 'max' => 45],
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
            'memo' => 'Memo',
            'created_by' => 'Created By',
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
     * @return MemoLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MemoLogsQuery(get_called_class());
    }
}
