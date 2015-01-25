# Translateable Behavior for Yii 2

[![Build Status](https://img.shields.io/travis/creocoder/yii2-translateable/master.svg?style=flat-square)](https://travis-ci.org/creocoder/yii2-translateable)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/creocoder/yii2-translateable/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/creocoder/yii2-translateable/?branch=master)

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

## Usage

TBD.

## Donating

Support this project and [others by creocoder](https://gratipay.com/creocoder/) via [gratipay](https://gratipay.com/creocoder/).

[![Support via Gratipay](https://cdn.rawgit.com/gratipay/gratipay-badge/2.3.0/dist/gratipay.svg)](https://gratipay.com/creocoder/)
