<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_banned_users".
 *
 * @property string $ID
 * @property string $user_id
 * @property string $banned_user_id
 *
 * @property Users $user
 */
class BannedUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_banned_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'banned_user_id'], 'required'],
            [['user_id', 'banned_user_id'], 'integer'],
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
            'banned_user_id' => 'Banned User ID',
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
     * @return BannedUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BannedUsersQuery(get_called_class());
    }
}
