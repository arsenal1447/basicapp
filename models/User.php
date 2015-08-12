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
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
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
            [['id', 'username', 'password', 'salt'], 'required'],
            [['id'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 100],
            [['salt'], 'string', 'max' => 10],
            [['email'],'filter', 'filter' => 'trim'],
            [['email'],'required'],
            [['email'],'email'],
            [['email'],'unique']
        ];
    }
    
    /**
     * Creates a new user
     *
     * @param array $attributes
     *        	the attributes given by field => value
     * @return static null newly created model, or null on failure
     */
    public static function create($attributes)
    {
        /**
         * @var User $user
         */
        $user = new static();
        $user->setAttributes($attributes);
        $user->setPassword($attributes['password']);
        $user->generateAuthKey();
        if ($user->save())
        {
            return $user;
        }
        else
        {
            return null;
        }
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
            'salt' => 'Salt',
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
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
	    return $this->getAuthKey() === $authKey;
	}
}
