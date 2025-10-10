<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_coupon".
 *
 * @property string $ID
 * @property string $coupon_name
 * @property string $coupon_type
 * @property string $coupon_desc
 * @property string $period_from
 * @property string $period_to
 * @property string $discount_type
 * @property int $discount_amount 할인 금액 
 * @property int $discount_rate 할인율 
 * @property int $item_price_min 최소 사용 가능 금액 
 * @property int $item_price_max 최대 할인 가능 금액 
 * @property string $related_publisher 쿠폰 적용 출판사 
 * @property string $created_dt
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_name', 'coupon_type', 'coupon_desc', 'discount_type', 'discount_amount', 'discount_rate', 'item_price_min', 'item_price_max', 'created_dt'], 'required'],
            [['coupon_desc'], 'string'],
            [['period_from', 'period_to', 'created_dt'], 'safe'],
            [['discount_amount', 'discount_rate', 'item_price_min', 'item_price_max', 'related_publisher'], 'integer'],
            [['coupon_name'], 'string', 'max' => 200],
            [['coupon_type', 'discount_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'coupon_name' => 'Coupon Name',
            'coupon_type' => 'Coupon Type',
            'coupon_desc' => 'Coupon Desc',
            'period_from' => 'Period From',
            'period_to' => 'Period To',
            'discount_type' => 'Discount Type',
            'discount_amount' => 'Discount Amount',
            'discount_rate' => 'Discount Rate',
            'item_price_min' => 'Item Price Min',
            'item_price_max' => 'Item Price Max',
            'related_publisher' => 'Related Publisher',
            'created_dt' => 'Created Dt',
        ];
    }

    /**
     * @inheritdoc
     * @return CouponQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CouponQuery(get_called_class());
    }
}
