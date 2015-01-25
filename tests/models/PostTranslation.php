<?php
/**
 * @link https://github.com/creocoder/yii2-translateable
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace tests\models;

/**
 * PostTranslation
 *
 * @property integer $post_id
 * @property string $language
 * @property string $title
 * @property string $body
 */
class PostTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            ['body', 'required'],
            ['body', 'string'],
        ];
    }
}
