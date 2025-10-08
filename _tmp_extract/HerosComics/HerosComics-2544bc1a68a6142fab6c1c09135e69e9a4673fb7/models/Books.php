<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_books".
 *
 * @property string $ID 도서 아이디
 * @property string $book_title 도서 제목
 * @property string $author 저자
 * @property string $publisher 출판사
 * @property string $isbn ISBN
 * @property int $normal_price 정가
 * @property int $discount_rate 할인율 
 * @property int $sale_price 판매가
 * @property string $cover_img 도서 커버 이미지
 * @property string $epub_path 전자책 파일 경로
 * @property string $epub_name 전자책 파일명
 * @property string $period_from 전자책 유효기간 from
 * @property string $period_to 전자책 유효기간 to
 * @property string $category_first 카테고리 대분류
 * @property string $category_second 카테고리 중분류
 * @property string $category_third 카테고리 소분류
 * @property string $is_pkg 단품 or Set
 * @property string $is_free 무료 여부
 * @property string $upload_type 등록형태 
 * @property int $book_status 처리상태 
 * @property string $created_dt 자료 생성일자
 * @property string $req_edit_dt 수정요청시간 
 * @property string $req_del_dt 삭제요청시간 
 * @property string $user_id
 * @property string $publisher_id 출판사 아이디, 1인 작가의 경우 북톡출판사 아이디 
 *
 * @property Activity[] $activities
 * @property Users $user
 * @property BooksMeta[] $booksMetas
 * @property OrderItem[] $orderItems
 * @property UserBookRead[] $userBookReads
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_title', 'author', 'publisher', 'isbn', 'normal_price', 'sale_price', 'epub_path', 'upload_type', 'book_status', 'created_dt', 'user_id', 'publisher_id'], 'required'],
            [['normal_price', 'discount_rate', 'sale_price', 'category_first', 'category_second', 'category_third', 'book_status', 'user_id', 'publisher_id'], 'integer'],
            [['period_from', 'period_to', 'created_dt', 'req_edit_dt', 'req_del_dt'], 'safe'],
            [['book_title', 'author', 'publisher'], 'string', 'max' => 255],
            [['isbn', 'upload_type'], 'string', 'max' => 20],
            [['cover_img'], 'string', 'max' => 150],
            [['epub_path', 'epub_name'], 'string', 'max' => 1000],
            [['is_pkg', 'is_free'], 'string', 'max' => 5],
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
            'book_title' => 'Book Title',
            'author' => 'Author',
            'publisher' => 'Publisher',
            'isbn' => 'Isbn',
            'normal_price' => 'Normal Price',
            'discount_rate' => 'Discount Rate',
            'sale_price' => 'Sale Price',
            'cover_img' => 'Cover Img',
            'epub_path' => 'Epub Path',
            'epub_name' => 'Epub Name',
            'period_from' => 'Period From',
            'period_to' => 'Period To',
            'category_first' => 'Category First',
            'category_second' => 'Category Second',
            'category_third' => 'Category Third',
            'is_pkg' => 'Is Pkg',
            'is_free' => 'Is Free',
            'upload_type' => 'Upload Type',
            'book_status' => 'Book Status',
            'created_dt' => 'Created Dt',
            'req_edit_dt' => 'Req Edit Dt',
            'req_del_dt' => 'Req Del Dt',
            'user_id' => 'User ID',
            'publisher_id' => 'Publisher ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['book_id' => 'ID']);
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
    public function getBooksMetas()
    {
        return $this->hasMany(BooksMeta::className(), ['book_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['book_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBookReads()
    {
        return $this->hasMany(UserBookRead::className(), ['book_id' => 'ID']);
    }

    /**
     * @inheritdoc
     * @return BooksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BooksQuery(get_called_class());
    }
}
