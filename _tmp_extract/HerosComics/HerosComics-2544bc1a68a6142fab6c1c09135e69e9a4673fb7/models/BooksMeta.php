<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_books_meta".
 *
 * @property string $bmeta_id
 * @property string $book_id
 * @property string $meta_key
 * @property string $meta_value
 *
 * @property Books $book
 */
class BooksMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_books_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id'], 'required'],
            [['book_id'], 'integer'],
            [['meta_value'], 'string'],
            [['meta_key'], 'string', 'max' => 255],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::className(), 'targetAttribute' => ['book_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bmeta_id' => 'Bmeta ID',
            'book_id' => 'Book ID',
            'meta_key' => 'Meta Key',
            'meta_value' => 'Meta Value',
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
     * @inheritdoc
     * @return BooksMetaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BooksMetaQuery(get_called_class());
    }
}
