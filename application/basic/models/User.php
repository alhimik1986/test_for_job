<?php

namespace app\models;


class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

	private static function getUserByParam($field, $value)
	{
		return Users::find([$field=>$value])->asArray()->one();
	}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
		$user = self::getUserByParam('id', $id);
        return $user ? new static($user) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		$user = self::getUserByParam('accessToken', $token);
        return $user ? new static($user) : null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
		$user = self::getUserByParam('username', $username);
        return $user ? new static($user) : null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
