<?php

namespace dsj\rbac\models;

/**
 * This is the model class for table "rbac_item".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $description
 * @property int $rule_id
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class RbacItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rbac_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description'], 'required'],
            [['type', 'rule_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['description', 'data'], 'string', 'max' => 500],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '路由',
            'type' => 'Type',
            'description' => '描述',
            'rule_id' => '规则名称',
            'data' => '规则数据',
            'created_at' => '创建时间',
            'updated_at' => '最后修改时间',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($insert){
                $this->created_at = time();
                $this->updated_at = time();
            }else{
                $this->updated_at = time();
            }
            return true;
        }else{
            return false;
        }
    }

    public static function getAllRoles(){
        return self::find()->select('name,description')->where(['type' => '1'])->asArray()->all();
    }
}
