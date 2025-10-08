<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_activity".
 *
 * @property string $id
 * @property string $user_id
 * @property string $book_id
 * @property string $component
 * @property string $type
 * @property string $subject
 * @property string $content
 * @property string $item_id
 * @property string $secondary_item_id
 * @property int $hide_sitewide
 * @property string $created_dt
 * @property int $count_hit 조회 수 
 * @property int $count_like 추천 수 
 * @property int $count_comment 댓글 수 
 * @property string $is_deleted 삭제여부 
 * @property string $deleted_dt
 *
 * @property Books $book
 * @property Users $user
 * @property ActivityComment[] $activityComments
 * @property ActivityMeta[] $activityMetas
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'book_id', 'component', 'type', 'subject', 'content', 'item_id', 'secondary_item_id', 'hide_sitewide', 'created_dt', 'count_hit', 'count_like', 'count_comment'], 'required'],
            [['user_id', 'book_id', 'item_id', 'secondary_item_id', 'hide_sitewide', 'count_hit', 'count_like', 'count_comment'], 'integer'],
            [['content'], 'string'],
            [['created_dt', 'deleted_dt'], 'safe'],
            [['component', 'type'], 'string', 'max' => 45],
            [['subject'], 'string', 'max' => 200],
            [['is_deleted'], 'string', 'max' => 10],
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
            'id' => 'ID',
            'user_id' => 'User ID',
            'book_id' => 'Book ID',
            'component' => 'Component',
            'type' => 'Type',
            'subject' => 'Subject',
            'content' => 'Content',
            'item_id' => 'Item ID',
            'secondary_item_id' => 'Secondary Item ID',
            'hide_sitewide' => 'Hide Sitewide',
            'created_dt' => 'Created Dt',
            'count_hit' => 'Count Hit',
            'count_like' => 'Count Like',
            'count_comment' => 'Count Comment',
            'is_deleted' => 'Is Deleted',
            'deleted_dt' => 'Deleted Dt',
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
     * @return \yii\db\ActiveQuery
     */
    public function getActivityComments()
    {
        return $this->hasMany(ActivityComment::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityMetas()
    {
        return $this->hasMany(ActivityMeta::className(), ['activity_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityQuery(get_called_class());
    }
}
