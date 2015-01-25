<?php
/**
 * @link https://github.com/creocoder/yii2-translateable
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace tests;

use tests\models\Post;
use Yii;
use yii\db\Connection;

/**
 * TranslateableBehaviorTest
 */
class TranslateableBehaviorTest extends DatabaseTestCase
{
    public function testFindPosts()
    {
        $data = [];
        $models = Post::find()->with('translations')->all();

        foreach ($models as $model) {
            $data[] = $model->toArray([], ['translations']);
        }

        $this->assertEquals(require(__DIR__ . '/data/test-find-posts.php'), $data);
    }

    public function testCreatePost()
    {
        $post = new Post();
        $post->title = 'Incomplete post';
        $this->assertFalse($post->save());

        $post = new Post();
        $post->title = 'Post title 4';
        $post->body = 'Post body 4';
        $post->translate('de-DE')->title = 'Unvollständig post';
        $this->assertFalse($post->save());

        $post = new Post();
        $post->title = 'Post title 4';
        $post->body = 'Post body 4';
        $post->translate('de-DE')->title = 'Post titel 4';
        $post->translate('de-DE')->body = 'Post inhalt 4';
        $post->translate('ru-RU')->title = 'Заголовок поста 4';
        $post->translate('ru-RU')->body = 'Тело поста 4';
        $this->assertTrue($post->save());

        $dataSet = $this->getConnection()->createDataSet(['post', 'post_translation']);
        \PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet::write($dataSet, __DIR__ . '/data/test-create-post.xml');
        $expectedDataSet = $this->createFlatXMLDataSet(__DIR__ . '/data/test-create-post.xml');
        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    public function testUpdatePost()
    {
        $post = Post::findOne(2);
        $post->title = 'Updated post title 2';
        $post->body = 'Updated post body 2';
        $post->translate('de-DE')->title = 'Aktualisiert post titel 2';
        $post->translate('de-DE')->body = 'Aktualisiert post inhalt 2';
        $post->translate('ru-RU')->title = 'Обновленный заголовок поста 2';
        $post->translate('ru-RU')->body = 'Обновленное тело поста 2';
        $this->assertTrue($post->save());

        $dataSet = $this->getConnection()->createDataSet(['post', 'post_translation']);
        \PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet::write($dataSet, __DIR__ . '/data/test-update-post.xml');
        $expectedDataSet = $this->createFlatXMLDataSet(__DIR__ . '/data/test-update-post.xml');
        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    public function testPostHasTranslation()
    {
        $post = new Post();
        $this->assertFalse($post->hasTranslation());
        $this->assertFalse($post->hasTranslation('en-US'));
        $post = Post::findOne(2);
        $this->assertTrue($post->hasTranslation());
        $this->assertTrue($post->hasTranslation('en-US'));
        $this->assertTrue($post->hasTranslation('de-DE'));
        $this->assertTrue($post->hasTranslation('ru-RU'));
    }

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        try {
            Yii::$app->set('db', [
                'class' => Connection::className(),
                'dsn' => 'sqlite::memory:',
            ]);

            Yii::$app->getDb()->open();
            $lines = explode(';', file_get_contents(__DIR__ . '/migrations/sqlite.sql'));

            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    Yii::$app->getDb()->pdo->exec($line);
                }
            }
        } catch (\Exception $e) {
            Yii::$app->clear('db');
        }
    }
}
