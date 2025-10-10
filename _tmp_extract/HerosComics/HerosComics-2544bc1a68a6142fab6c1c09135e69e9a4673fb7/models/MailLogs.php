<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_mail_logs".
 *
 * @property string $ID
 * @property string $user_id
 * @property string $message 메 내용
 * @property string $mail_type 메일 성격 
 * @property string $sent_dt
 *
 * @property Users $user
 */
class MailLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_mail_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message', 'mail_type', 'sent_dt'], 'required'],
            [['user_id'], 'integer'],
            [['message'], 'string'],
            [['sent_dt'], 'safe'],
            [['mail_type'], 'string', 'max' => 45],
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
            'message' => 'Message',
            'mail_type' => 'Mail Type',
            'sent_dt' => 'Sent Dt',
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
     * @return MailLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MailLogsQuery(get_called_class());
    }
}
