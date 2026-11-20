<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app".
 *
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property string $start
 * @property string $payment
 * @property string $status
 * @property string|null $feedback
 *
 * @property Room $room
 * @property User $user
 */
class app extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feedback'], 'default', 'value' => null],
            [['payment'], 'default', 'value' => 'При очном посещении'],
            [['status'], 'default', 'value' => 'Новая'],
            [['user_id', 'room_id', 'start'], 'required'],
            [['user_id', 'room_id'], 'integer'],
            [['start'], 'safe'],
            [['payment', 'status', 'feedback'], 'string'],
            [['user_id', 'room_id'], 'unique', 'targetAttribute' => ['user_id', 'room_id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'room_id' => 'Помещение',
            'start' => 'Дата начала конференции',
            'payment' => 'Способ оплаты',
            'status' => 'Статус',
            'feedback' => 'Отзыв',
        ];
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
