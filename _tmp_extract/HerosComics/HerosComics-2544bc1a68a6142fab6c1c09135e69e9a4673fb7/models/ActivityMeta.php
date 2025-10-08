<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_activity_meta".
 *
 * @property string $meta_id
 * @property string $activity_id
 * @property string $meta_key
 * @property string $meta_value
 *
 * @property Activity $activity
 */
class ActivityMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_activity_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'meta_key', 'meta_value'], 'required'],
            [['activity_id'], 'integer'],
            [['meta_value'], 'string'],
            [['meta_key'], 'string', 'max' => 255],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'meta_id' => 'Meta ID',
            'activity_id' => 'Activity ID',
            'meta_key' => 'Meta Key',
            'meta_value' => 'Meta Value',
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
     * @return ActivityMetaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityMetaQuery(get_called_class());
    }
}
