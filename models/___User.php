<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 *
 * @property RoleUser[] $roleUsers
 */
class User extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;

	const STATUS_ACTIVE = 10;

	const ROLE_USER = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            //'salt' => 'Salt',
            'Email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleUsers()
    {
        return $this->hasMany(RoleUser::className(), ['user_id' => 'id']);
    }
    /**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token,$type = null)
	{
		throw new NotSupportedException(
				'"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static null
	 */
	public static function findByUsername($username)
	{
	    /**
	    $user = User::find()
	    ->where(['username' => $username])
	    ->asArray()
	    ->one();

	    if($user){
	        return new static($user);
	    }

	    return null;

	    */
		return static::findOne(
				[
						'username' => $username,
						'status' => self::STATUS_ACTIVE
				]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token
	 *        	password reset token
	 * @return static null
	 */
	public static function findByPasswordResetToken($token)
	{
		$expire = \Yii::$app->params['user.passwordResetTokenExpire'];
		$parts = explode('_', $token);
		$timestamp = (int) end($parts);
		if ($timestamp + $expire < time())
		{
			// token expired
			return null;
		}

		return static::findOne(
				[
						'password_reset_token' => $token,
						'status' => self::STATUS_ACTIVE
				]);
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
	    return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
	    return $this->auth_key;
	}
	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
	    //$this->auth_key = Security::generateRandomKey();
	    $this->auth_key = Yii::$app->security->generateRandomKey();
	}


	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
	    //$this->password_hash = Security::generatePasswordHash($password);
	    $this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}
	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
	    return $this->getAuthKey() === $authKey;
	}
}
