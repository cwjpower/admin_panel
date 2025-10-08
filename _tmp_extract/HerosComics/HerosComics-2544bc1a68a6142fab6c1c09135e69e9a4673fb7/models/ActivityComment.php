<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_activity_comment".
 *
 * @property string $comment_id
 * @property string $comment_author
 * @property string $comment_author_ip
 * @property string $comment_date
 * @property string $comment_content
 * @property int $comment_read 게시글 등록자가 댓글 읽었는지 여부 
 * @property string $activity_id
 * @property string $comment_user_id
 * @property int $comment_user_level
 *
 * @property Activity $activity
 */
class ActivityComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_activity_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_author', 'comment_author_ip', 'comment_date', 'comment_content', 'activity_id', 'comment_user_id', 'comment_user_level'], 'required'],
            [['comment_date'], 'safe'],
            [['comment_content'], 'string'],
            [['comment_read', 'activity_id', 'comment_user_id', 'comment_user_level'], 'integer'],
            [['comment_author', 'comment_author_ip'], 'string', 'max' => 100],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'comment_author' => 'Comment Author',
            'comment_author_ip' => 'Comment Author Ip',
            'comment_date' => 'Comment Date',
            'comment_content' => 'Comment Content',
            'comment_read' => 'Comment Read',
            'activity_id' => 'Activity ID',
            'comment_user_id' => 'Comment User ID',
            'comment_user_level' => 'Comment User Level',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }

    /**
     * @inheritdoc
     * @return ActivityCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityCommentQuery(get_called_class());
    }
}
