<?php

namespace app\models;


use yii\base\Model;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property integer $status
 *
 * @property RoleUser[] $roleUsers
 */
class SignUpForm extends \yii\db\ActiveRecord
{
    public $username;
    public $email;
    public $password;
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
            [['username', 'password',], 'required'],
            [['status'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 100],
            //[['salt'], 'string', 'max' => 10],
            [['email'], 'filter', 'filter' => 'trim'],
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.']
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleUsers()
    {
        return $this->hasMany(RoleUser::className(), ['user_id' => 'id']);
    }


    public function signup(){
        if($this->validate()){
            if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }
        }
        return null;
    }







}
