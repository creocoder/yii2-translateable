<?php
/**
 * @link https://github.com/creocoder/yii2-translateable
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace tests;

use creocoder\translateable\TranslateableBehavior;
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
        $posts = Post::find()->with('translations')->all();

        foreach ($posts as $post) {
            $data[] = $post->toArray([], ['translations']);
        }

        $this->assertEquals(require(__DIR__ . '/data/test-find-posts.php'), $data);
    }

    public function testFindPost()
    {
        $post = Post::findOne(2);
        $this->assertEquals('Post title 2', $post->title);
        $this->assertEquals('Post body 2', $post->body);
        $this->assertEquals('Post title 2', $post->translate()->title);
        $this->assertEquals('Post body 2', $post->translate()->body);
        $this->assertEquals('Post titel 2', $post->translate('de-DE')->title);
        $this->assertEquals('Post inhalt 2', $post->translate('de-DE')->body);
        $this->assertEquals('Заголовок поста 2', $post->translate('ru-RU')->title);
        $this->assertEquals('Тело поста 2', $post->translate('ru-RU')->body);
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
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testExceptionIsRaisedWhenTranslationAttributesPropertyIsNotSet()
    {
        new TranslateableBehavior();
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
