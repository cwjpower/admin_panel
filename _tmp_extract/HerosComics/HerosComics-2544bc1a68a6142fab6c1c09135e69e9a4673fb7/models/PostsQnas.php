<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_posts_qnas".
 *
 * @property string $ID
 * @property string $post_type
 * @property string $post_name
 * @property string $post_content
 * @property string $post_title
 * @property string $post_date
 * @property string $post_term_id 카테고리 ID
 * @property string $post_user_id
 * @property string $post_status
 * @property string $post_attachment
 * @property string $post_ans_title
 * @property string $post_ans_content
 * @property string $post_ans_attachment
 * @property string $post_ans_user_id
 * @property string $post_ans_date
 */
class PostsQnas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_posts_qnas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_type', 'post_name', 'post_content', 'post_title', 'post_date', 'post_term_id', 'post_user_id', 'post_status', 'post_attachment'], 'required'],
            [['post_content', 'post_attachment', 'post_ans_content', 'post_ans_attachment'], 'string'],
            [['post_date', 'post_ans_date'], 'safe'],
            [['post_term_id', 'post_user_id', 'post_ans_user_id'], 'integer'],
            [['post_type'], 'string', 'max' => 45],
            [['post_name', 'post_title', 'post_ans_title'], 'string', 'max' => 200],
            [['post_status'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'post_type' => 'Post Type',
            'post_name' => 'Post Name',
            'post_content' => 'Post Content',
            'post_title' => 'Post Title',
            'post_date' => 'Post Date',
            'post_term_id' => 'Post Term ID',
            'post_user_id' => 'Post User ID',
            'post_status' => 'Post Status',
            'post_attachment' => 'Post Attachment',
            'post_ans_title' => 'Post Ans Title',
            'post_ans_content' => 'Post Ans Content',
            'post_ans_attachment' => 'Post Ans Attachment',
            'post_ans_user_id' => 'Post Ans User ID',
            'post_ans_date' => 'Post Ans Date',
        ];
    }

    /**
     * @inheritdoc
     * @return PostsQnasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostsQnasQuery(get_called_class());
    }
}
