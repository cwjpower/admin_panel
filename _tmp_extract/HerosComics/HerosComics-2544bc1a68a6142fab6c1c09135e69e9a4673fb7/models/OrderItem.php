<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_order_item".
 *
 * @property string $item_id
 * @property string $order_id
 * @property string $book_id
 * @property int $original_price
 * @property int $sale_price
 * @property string $book_title
 * @property string $epub_url private epub URL
 * @property int $book_dc_rate 책 다운로드 완료 시각 
 * @property string $book_down_dt
 *
 * @property Books $book
 * @property Order $order
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'book_id', 'original_price', 'sale_price', 'book_title', 'epub_url', 'book_dc_rate'], 'required'],
            [['order_id', 'book_id', 'original_price', 'sale_price', 'book_dc_rate'], 'integer'],
            [['book_down_dt'], 'safe'],
            [['book_title'], 'string', 'max' => 255],
            [['epub_url'], 'string', 'max' => 1000],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::className(), 'targetAttribute' => ['book_id' => 'ID']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'order_id' => 'Order ID',
            'book_id' => 'Book ID',
            'original_price' => 'Original Price',
            'sale_price' => 'Sale Price',
            'book_title' => 'Book Title',
            'epub_url' => 'Epub Url',
            'book_dc_rate' => 'Book Dc Rate',
            'book_down_dt' => 'Book Down Dt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::className(), ['ID' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return OrderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderItemQuery(get_called_class());
    }
}
