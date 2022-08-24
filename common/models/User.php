<?php

namespace common\models;

use common\models\base\User as BaseUser;
use common\models\traits\Person;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property string $password write-only password
 */
class User extends BaseUser implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 0;

    use Person;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_map(function ($array) {
            if ($array['class'] == \yii\behaviors\TimestampBehavior::className()) {
                $array['value'] = new \yii\db\Expression('current_timestamp');
            }
            return $array;
        }, parent::behaviors());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['email'], 'email']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
        return ($user && !$user->getDriver()->exists()) ? $user : null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getUsername()
    {
        if (preg_match('/^[^@]+/', $this->email, $regs)) {
            $result = $regs[0];
        } else {
            $result = $this->email;;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function get_label()
    {
        return $this->username;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'department_id' => Yii::t('app', 'Department'),
            'default_office_id' => Yii::t('app', 'Default Office'),
            'agent_and_subject_to_agent_rules' => Yii::t('app', 'User is an Agent and is subject to Agent rules'),
            'can_access_dispatch_data_from_all_offices' => Yii::t('app', 'User can access dispatch data from all offices'),
        ]);
    }

    public static function updateSessionId()
    {
        if(Yii::$app->user->id){
            //update the user table with last_session_id
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $user->last_session_id = Yii::$app->session->id;
            $user->save(false);
        }
    }

    public static function checkSessionId()
    {
        if(Yii::$app->user->id){
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            if ($user) {
                $user->touch('last_activity');
            }
            return $user->last_session_id == Yii::$app->session->id;
        }

        return true;
    }
}