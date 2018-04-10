<?php
$root = dirname(dirname(__DIR__));
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', $root . '/frontend');
Yii::setAlias('@backend', $root . '/backend');
Yii::setAlias('@facilitator', $root . '/facilitator');
Yii::setAlias('@console', $root . '/console');
Yii::setAlias('@upload', $root . '/upload');
Yii::setAlias('@root', $root);
Yii::setAlias('@webRoot', $root);
