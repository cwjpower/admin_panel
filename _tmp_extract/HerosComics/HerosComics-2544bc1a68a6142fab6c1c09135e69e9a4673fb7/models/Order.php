<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_order".
 *
 * @property string $order_id
 * @property int $order_status
 * @property string $user_id
 * @property int $total_amount 합계금액 
 * @property int $coupon_discount
 * @property int $discount_amount 실제 판매금액 
 * @property int $cybercash_paid 사용한 CASH
 * @property int $cyberpoint_paid 사용한 적립금 
 * @property int $total_paid 실 결제금액 
 * @property int $total_refund_amount 총 환불금액 
 * @property int $cybercash_refunded CASH 환불금액 
 * @property int $cyberpoint_refunded 적립금 환불금액 
 * @property int $total_refunded 실 환불금액 
 * @property string $created_dt
 * @property string $updated_dt
 * @property string $remote_ip
 * @property string $coupon_code 쿠폰코드 
 * @property string $coupon_name 쿠폰명 
 * @property string $refunded_dt
 *
 * @property Users $user
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status', 'user_id', 'total_amount', 'discount_amount', 'cybercash_paid', 'cyberpoint_paid', 'total_paid', 'created_dt', 'updated_dt', 'remote_ip'], 'required'],
            [['order_status', 'user_id', 'total_amount', 'coupon_discount', 'discount_amount', 'cybercash_paid', 'cyberpoint_paid', 'total_paid', 'total_refund_amount', 'cybercash_refunded', 'cyberpoint_refunded', 'total_refunded'], 'integer'],
            [['created_dt', 'updated_dt', 'refunded_dt'], 'safe'],
            [['coupon_name'], 'string'],
            [['remote_ip'], 'string', 'max' => 45],
            [['coupon_code'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_status' => 'Order Status',
            'user_id' => 'User ID',
            'total_amount' => 'Total Amount',
            'coupon_discount' => 'Coupon Discount',
            'discount_amount' => 'Discount Amount',
            'cybercash_paid' => 'Cybercash Paid',
            'cyberpoint_paid' => 'Cyberpoint Paid',
            'total_paid' => 'Total Paid',
            'total_refund_amount' => 'Total Refund Amount',
            'cybercash_refunded' => 'Cybercash Refunded',
            'cyberpoint_refunded' => 'Cyberpoint Refunded',
            'total_refunded' => 'Total Refunded',
            'created_dt' => 'Created Dt',
            'updated_dt' => 'Updated Dt',
            'remote_ip' => 'Remote Ip',
            'coupon_code' => 'Coupon Code',
            'coupon_name' => 'Coupon Name',
            'refunded_dt' => 'Refunded Dt',
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
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }
}
