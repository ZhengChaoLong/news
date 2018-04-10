<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 19:20
 */

namespace backend\controllers;



class UeditorController extends \crazydb\ueditor\UEditorController
{
//    public $config = [
//        'imagePathFormat' => '../../upload/image/{yyyy}{mm}{dd}/{time}{rand:8}',
//        'scrawlPathFormat' => '../../upload/image/{yyyy}{mm}{dd}/{time}{rand:8}',
//        'snapscreenPathFormat' => '../../upload/image/{yyyy}{mm}{dd}/{time}{rand:8}',
//        'catcherPathFormat' => '../../upload/image/{yyyy}{mm}{dd}/{time}{rand:8}',
//        'videoPathFormat' => '../../upload/video/{yyyy}{mm}{dd}/{time}{rand:8}',
//        'filePathFormat' => '../../upload/file/{yyyy}{mm}{dd}/{rand:8}_{filename}',
//        'imageManagerListPath' => '../../upload/image/',
//        'fileManagerListPath' => '../../upload/file/',
//        'imageCompressEnable' => false,
//    ];

    protected function upload($fieldName, $config, $base64 = 'upload')
    {
        $info = parent::upload($fieldName, $config, $base64);
        $info['url'] = \Yii::getAlias('@staticWebPath') . $info['url'];
        return $info;
    }

    protected function manage($allowFiles, $listSize, $path)
    {
        $result = parent::manage($allowFiles, $listSize, $path);
        array_walk($result['list'], function(&$value){
            $value['url'] = \Yii::getAlias('@staticWebPath') . $value['url'];
        });
        return $result;
    }
}
