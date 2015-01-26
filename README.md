# Translateable Behavior for Yii 2

[![Build Status](https://img.shields.io/travis/creocoder/yii2-translateable/master.svg?style=flat-square)](https://travis-ci.org/creocoder/yii2-translateable)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/creocoder/yii2-translateable/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/creocoder/yii2-translateable/?branch=master)
[![Code Quality](https://img.shields.io/scrutinizer/g/creocoder/yii2-translateable/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/creocoder/yii2-translateable/?branch=master)

A modern translateable behavior for the Yii framework.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ php composer.phar require creocoder/yii2-translateable:dev-master
```

or add

```
"creocoder/yii2-translateable": "dev-master"
```

to the `require` section of your `composer.json` file.

## Migrations

Run the following command

```bash
$ yii migrate/create create_post_table
```

Open the `/path/to/migrations/m_xxxxxx_xxxxxx_create_post_table.php` file,
inside the `up()` method add the following

```php
$this->createTable('{{%post}}', [
    'id' => Schema::TYPE_PK,
]);
```

Run the following command

```bash
$ yii migrate/create create_post_translation_table
```

Open the `/path/to/migrations/m_xxxxxx_xxxxxx_create_post_translation_table.php` file,
inside the `up()` method add the following

```php
$this->createTable('{{%post_translation}}', [
    'post_id' => Schema::TYPE_INTEGER . ' NOT NULL',
    'language' => Schema::TYPE_STRING . '(16) NOT NULL',
    'title' => Schema::TYPE_STRING . ' NOT NULL',
    'body' => Schema::TYPE_TEXT . ' NOT NULL',
]);

$this->addPrimaryKey('', '{{%post_translation}}', ['post_id', 'language']);
```

## Configuring

Configure model as follows

```php
use creocoder\translateable\TranslateableBehavior;

/**
 * ...
 * @property string $title
 * @property string $body
 * ...
 */
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'body'],
                // translationRelation => 'translations',
                // translationLanguageAttribute => 'language',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | OP_UPDATE,
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(PostTranslation::className(), ['post_id' => 'id']);
    }
}
```

Model `PostTranslation` can be generated using Gii.

## Usage

### Setting translations to the entity

To set translations to the entity

```php
$post = new Post();

// title attribute translation for default application language
$post->title = 'Post title';

// body attribute translation for default application language
$post->body = 'Post body';

// title attribute translation for German
$post->translate('de-DE')->title = 'Post titel';

// body attribute translation for German
$post->translate('de-DE')->title = 'Post inhalt';

// title attribute translation for Russian
$post->translate('ru-RU')->title = 'Заголовок поста';

// body attribute translation for Russian
$post->translate('ru-RU')->title = 'Заголовок поста';

// save post and its translations
$post->save();
```

### Getting translations from the entity

To get translations from the entity

```php
$posts = Post::find()->with('translations')->all();

foreach ($posts as $post) {
    // title attribute translation for default application language
    $title = $post->title;

    // body attribute translation for default application language
    $body = $post->body;

    // title attribute translation for German
    $germanTitle = $post->translate('de-DE')->title;

    // body attribute translation for German
    $germanBody = $post->translate('de-DE')->title;

    // title attribute translation for Russian
    $russianTitle = $post->translate('ru-RU')->title;

    // body attribute translation for Russian
    $russianBody = $post->translate('ru-RU')->title;
}
```

### Checking for translations in the entity

To check translations in the entity

```php
$post = Post::findOne(1);

// checking for default application language translation
$result = $post->hasTranslation();

// checking for German translation
$result = $post->hasTranslation('de-DE');

// checking for Russian translation
$result = $post->hasTranslation('ru-RU');
```

## Donating

Support this project and [others by creocoder](https://gratipay.com/creocoder/) via [gratipay](https://gratipay.com/creocoder/).

[![Support via Gratipay](https://cdn.rawgit.com/gratipay/gratipay-badge/2.3.0/dist/gratipay.svg)](https://gratipay.com/creocoder/)
