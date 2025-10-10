<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_posts".
 *
 * @property string $ID
 * @property string $post_name
 * @property string $post_content
 * @property string $post_title
 * @property string $post_date
 * @property string $post_parent
 * @property string $post_status
 * @property string $post_password
 * @property string $post_user_id
 * @property string $post_email
 * @property int $post_order
 * @property string $post_type
 * @property string $post_type_secondary
 * @property string $post_type_area App/Web/답벼락/채팅 
 * @property string $post_modified
 *
 * @property PostsMeta[] $postsMetas
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_name', 'post_content', 'post_title', 'post_date', 'post_parent', 'post_status', 'post_password', 'post_user_id', 'post_email', 'post_order', 'post_type', 'post_type_secondary', 'post_type_area', 'post_modified'], 'required'],
            [['post_content', 'post_title', 'post_type_area'], 'string'],
            [['post_date', 'post_modified'], 'safe'],
            [['post_parent', 'post_user_id', 'post_order'], 'integer'],
            [['post_name'], 'string', 'max' => 200],
            [['post_status', 'post_password'], 'string', 'max' => 20],
            [['post_email'], 'string', 'max' => 60],
            [['post_type', 'post_type_secondary'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'post_name' => 'Post Name',
            'post_content' => 'Post Content',
            'post_title' => 'Post Title',
            'post_date' => 'Post Date',
            'post_parent' => 'Post Parent',
            'post_status' => 'Post Status',
            'post_password' => 'Post Password',
            'post_user_id' => 'Post User ID',
            'post_email' => 'Post Email',
            'post_order' => 'Post Order',
            'post_type' => 'Post Type',
            'post_type_secondary' => 'Post Type Secondary',
            'post_type_area' => 'Post Type Area',
            'post_modified' => 'Post Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsMetas()
    {
        return $this->hasMany(PostsMeta::className(), ['post_id' => 'ID']);
    }

    /**
     * @inheritdoc
     * @return PostsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostsQuery(get_called_class());
    }
}
