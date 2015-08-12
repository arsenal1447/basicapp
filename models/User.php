<?php

namespace app\models;

use Yii;

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
            [['id', 'username', 'password', 'salt'], 'required'],
            [['id'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 100],
            [['salt'], 'string', 'max' => 10],
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
     * Finds user by username
     *
     * @param string $username
     * @return static null
     */
    public static function findByUsername($username){
        return static::findOne(
            [
            		'username' => $username,
            		'status' => self::STATUS_ACTIVE
            ]);
    }
}
