<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_app_mobile_auth".
 *
 * @property string $ID
 * @property string $mobile
 * @property string $authcode
 * @property string $created_dt
 */
class AppMobileAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_app_mobile_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'authcode', 'created_dt'], 'required'],
            [['created_dt'], 'safe'],
            [['mobile'], 'string', 'max' => 12],
            [['authcode'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'mobile' => 'Mobile',
            'authcode' => 'Authcode',
            'created_dt' => 'Created Dt',
        ];
    }

    /**
     * @inheritdoc
     * @return AppMobileAuthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppMobileAuthQuery(get_called_class());
    }
}
