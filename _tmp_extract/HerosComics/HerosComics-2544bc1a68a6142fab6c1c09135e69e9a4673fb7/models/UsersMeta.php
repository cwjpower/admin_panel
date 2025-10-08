<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_users_meta".
 *
 * @property string $umeta_id
 * @property string $user_id
 * @property string $meta_key
 * @property string $meta_value
 *
 * @property Users $user
 */
class UsersMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_users_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['meta_value'], 'string'],
            [['meta_key'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'umeta_id' => 'Umeta ID',
            'user_id' => 'User ID',
            'meta_key' => 'Meta Key',
            'meta_value' => 'Meta Value',
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
     * @return UsersMetaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersMetaQuery(get_called_class());
    }
}
