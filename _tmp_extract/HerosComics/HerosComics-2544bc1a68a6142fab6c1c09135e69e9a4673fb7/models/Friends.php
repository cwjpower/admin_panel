<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_friends".
 *
 * @property string $ID
 * @property string $initiator_user_id
 * @property string $friend_user_id
 * @property int $is_confirmed
 * @property int $is_limited
 * @property int $is_favorite 즐겨찾기 
 * @property string $date_created
 */
class Friends extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_friends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['initiator_user_id', 'friend_user_id', 'date_created'], 'required'],
            [['initiator_user_id', 'friend_user_id', 'is_confirmed', 'is_limited', 'is_favorite'], 'integer'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'initiator_user_id' => 'Initiator User ID',
            'friend_user_id' => 'Friend User ID',
            'is_confirmed' => 'Is Confirmed',
            'is_limited' => 'Is Limited',
            'is_favorite' => 'Is Favorite',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @inheritdoc
     * @return FriendsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FriendsQuery(get_called_class());
    }
}
