<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "itmo_todo_list".
 *
 * @property int $id
 * @property int $user_id
 * @property string $data
 *
 * @property TodoUser $user
 */
class TodoList extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'itmo_todo_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'data'], 'required'],
            [['user_id'], 'integer'],
            [['data'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => TodoUser::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TodoUser::class, ['id' => 'user_id']);
    }

}
