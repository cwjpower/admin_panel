<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_user_book_read".
 *
 * @property string $ID
 * @property string $user_id
 * @property string $book_id
 * @property string $read_dt_from
 * @property string $read_dt_to
 * @property int $read_page_to 최종 읽은 페이지 No.
 * @property string $epub_index
 *
 * @property Books $book
 * @property Users $user
 */
class UserBookRead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_user_book_read';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'book_id', 'read_dt_from', 'read_dt_to', 'read_page_to'], 'required'],
            [['user_id', 'book_id', 'read_page_to'], 'integer'],
            [['read_dt_from', 'read_dt_to'], 'safe'],
            [['epub_index'], 'string', 'max' => 45],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::className(), 'targetAttribute' => ['book_id' => 'ID']],
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
            'book_id' => 'Book ID',
            'read_dt_from' => 'Read Dt From',
            'read_dt_to' => 'Read Dt To',
            'read_page_to' => 'Read Page To',
            'epub_index' => 'Epub Index',
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
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['ID' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return UserBookReadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserBookReadQuery(get_called_class());
    }
}
