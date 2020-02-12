<?php

namespace dsj\rbac\models;

/**
 * This is the model class for table "rbac_item_child".
 *
 * @property int $id
 * @property string $parent
 * @property string $child
 */
class RbacItemChild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rbac_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }
}
