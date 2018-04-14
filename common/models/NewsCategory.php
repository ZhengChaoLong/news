<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/4/14
 * Time: 17:44
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class NewsCategory
 * @property integer $create_time
 * @property integer $update_time
 * @package common\models
 */
class NewsCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'news_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'parent_id' => '父级分类',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }


    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            if($insert) {
                $this->create_time = time();
                $this->update_time = time();
            } else {
                $this->update_time = time();
            }
            return true;
        } else {
            return false;
        }
    }


}
