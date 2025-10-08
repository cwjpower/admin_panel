<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_user_payment_list".
 *
 * @property string $ID
 * @property string $user_id
 * @property int $payment_amount 결제금액
 * @property string $payment_method
 * @property string $payment_state 결제 상태
 * @property int $point_amount 적립포인트 
 * @property string $created_dt 신청날짜
 * @property string $payment_dt 결제완료 날짜
 * @property string $meta_value
 *
 * @property Users $user
 */
class UserPaymentList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_user_payment_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'payment_amount', 'payment_method', 'payment_state', 'point_amount', 'created_dt', 'payment_dt', 'meta_value'], 'required'],
            [['user_id', 'payment_amount', 'point_amount'], 'integer'],
            [['created_dt', 'payment_dt'], 'safe'],
            [['meta_value'], 'string'],
            [['payment_method', 'payment_state'], 'string', 'max' => 40],
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
            'payment_amount' => 'Payment Amount',
            'payment_method' => 'Payment Method',
            'payment_state' => 'Payment State',
            'point_amount' => 'Point Amount',
            'created_dt' => 'Created Dt',
            'payment_dt' => 'Payment Dt',
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
     * @return UserPaymentListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserPaymentListQuery(get_called_class());
    }
}
