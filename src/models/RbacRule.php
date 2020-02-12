<?php

namespace dsj\rbac\models;


/**
 * This is the model class for table "rbac_rule".
 *
 * @property int $item_id
 * @property string $name
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class RbacRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rbac_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'name', 'data', 'created_at', 'updated_at'], 'required'],
            [['item_id'], 'integer'],
            [['name', 'data', 'created_at', 'updated_at'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'name' => 'Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
