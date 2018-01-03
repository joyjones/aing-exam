<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resource".
 *
 * @property integer $id
 * @property string $filename
 * @property string $media_type
 * @property string $md5
 */
class Resource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'md5'], 'string', 'max' => 255],
            [['media_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'filename' => Yii::t('common', 'Filename'),
            'media_type' => Yii::t('common', 'Media Type'),
            'md5' => Yii::t('common', 'Md5'),
        ];
    }
}
