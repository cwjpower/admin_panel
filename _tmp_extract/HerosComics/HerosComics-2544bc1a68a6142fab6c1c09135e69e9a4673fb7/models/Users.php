<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bt_users".
 *
 * @property string $ID 사용자 아이디
 * @property string $user_login 사용자 로그인 아이디
 * @property string $user_pass 로그인 패스워드
 * @property string $user_name 사용자명
 * @property string $user_email 사용자 이메일
 * @property string $display_name 닉네임
 * @property string $user_registered 가입일자
 * @property int $user_status 사용자 상태
 * @property int $user_level 사용자 등급
 * @property string $mobile 핸드폰번호
 * @property string $birthday 생년월일
 * @property string $gender 성별
 * @property int $residence 거주지역
 * @property int $last_school 최종학력
 * @property string $join_path 가입경로 
 * @property string $last_login_dt 마지막 로그인 일자
 *
 * @property Activity[] $activities
 * @property BannedUsers[] $bannedUsers
 * @property Banner[] $banners
 * @property Books[] $books
 * @property Curation[] $curations
 * @property MailLogs[] $mailLogs
 * @property MemoLogs[] $memoLogs
 * @property Order[] $orders
 * @property PublisherBook[] $publisherBooks
 * @property UserBookRead[] $userBookReads
 * @property UserCashLogs[] $userCashLogs
 * @property UserPaymentList[] $userPaymentLists
 * @property UsersMeta[] $usersMetas
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_login', 'user_pass', 'display_name', 'user_registered', 'user_status', 'user_level'], 'required'],
            [['user_registered', 'last_login_dt'], 'safe'],
            [['user_status', 'user_level', 'residence', 'last_school'], 'integer'],
            [['user_login', 'user_pass'], 'string', 'max' => 60],
            [['user_name'], 'string', 'max' => 45],
            [['user_email', 'display_name'], 'string', 'max' => 100],
            [['mobile', 'join_path'], 'string', 'max' => 20],
            [['birthday'], 'string', 'max' => 10],
            [['gender'], 'string', 'max' => 1],
            [['user_login'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_login' => 'User Login',
            'user_pass' => 'User Pass',
            'user_name' => 'User Name',
            'user_email' => 'User Email',
            'display_name' => 'Display Name',
            'user_registered' => 'User Registered',
            'user_status' => 'User Status',
            'user_level' => 'User Level',
            'mobile' => 'Mobile',
            'birthday' => 'Birthday',
            'gender' => 'Gender',
            'residence' => 'Residence',
            'last_school' => 'Last School',
            'join_path' => 'Join Path',
            'last_login_dt' => 'Last Login Dt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannedUsers()
    {
        return $this->hasMany(BannedUsers::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
        return $this->hasMany(Banner::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurations()
    {
        return $this->hasMany(Curation::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailLogs()
    {
        return $this->hasMany(MailLogs::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemoLogs()
    {
        return $this->hasMany(MemoLogs::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublisherBooks()
    {
        return $this->hasMany(PublisherBook::className(), ['publisher_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBookReads()
    {
        return $this->hasMany(UserBookRead::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCashLogs()
    {
        return $this->hasMany(UserCashLogs::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentLists()
    {
        return $this->hasMany(UserPaymentList::className(), ['user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersMetas()
    {
        return $this->hasMany(UsersMeta::className(), ['user_id' => 'ID']);
    }

    /**
     * @inheritdoc
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
}
