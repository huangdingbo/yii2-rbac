<?php

namespace dsj\rbac\models;

/**
 * This is the model class for table "rbac_assignment".
 *
 * @property int $id
 * @property string $item_name
 * @property string $user_id
 * @property string $created_at
 */
class RbacAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rbac_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id', 'created_at'], 'required'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    public static function getRolesByUser($user_id){
        $list = self::find()->leftJoin('rbac_item','rbac_assignment.item_name = rbac_item.name')
            ->select('rbac_item.name,rbac_item.description')
            ->where(['user_id' => $user_id])
            ->asArray()
            ->all();
        return $list;
    }
}
