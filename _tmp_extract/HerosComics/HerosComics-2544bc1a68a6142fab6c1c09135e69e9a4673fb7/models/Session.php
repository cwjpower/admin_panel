<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_session".
 *
 * @property string $sess_id
 * @property int $sess_expiry
 * @property string $sess_data
 * @property int $sess_created
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sess_id', 'sess_expiry', 'sess_data', 'sess_created'], 'required'],
            [['sess_expiry', 'sess_created'], 'integer'],
            [['sess_data'], 'string'],
            [['sess_id'], 'string', 'max' => 100],
            [['sess_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sess_id' => 'Sess ID',
            'sess_expiry' => 'Sess Expiry',
            'sess_data' => 'Sess Data',
            'sess_created' => 'Sess Created',
        ];
    }

    /**
     * @inheritdoc
     * @return SessionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SessionQuery(get_called_class());
    }
}
