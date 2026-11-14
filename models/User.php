<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $full_name
 * @property string $phone
 * @property string $email
 * @property int $admin
 *
 * @property App[] $apps
 * @property Room[] $rooms
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin'], 'default', 'value' => 0],
            [['username', 'password', 'full_name', 'phone', 'email'], 'required'],

            //
            ['username', 'match', 'pattern' => '/^[a-z0-9]{6,}$/iu',
            'message' => 'Латиница и цифры, не менее 6 символов'],
            ['full_name', 'match', 'pattern' => '/^[а-яё\s]+$/iu',
            'message' => 'Кириллица и пробелы'],
            [['password'], 'string', 'min' => 8],
            //
            [['admin'], 'integer'],
            [['username', 'password', 'full_name', 'phone', 'email'], 'string', 'max' => 255],

            //
            [['username'], 'unique'],
            //
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'full_name' => 'Full Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'admin' => 'Admin',
        ];
    }

    /**
     * Gets query for [[Apps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApps()
    {
        return $this->hasMany(App::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Rooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::class, ['id' => 'room_id'])->viaTable('app', ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // return static::findOne(['access_token' => $token]);
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // return $this->authKey;
        return null;
    }

    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
        return false;
    }

    /**
     * PKGH Валидация пароля
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * PKGH Поиск пользователя по имени
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['username'], $username) === 0) {
        //         return new static($user);
        //     }
        // }

        // return null;

        return static::findOne(['username' => $username]); //скопировала строчку из функции findIdentityByAccessToken
    }

}
