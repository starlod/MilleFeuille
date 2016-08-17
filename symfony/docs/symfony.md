# Symfony3コマンド

## Symfonyインストール

composer create-project symfony/framework-standard-edition ./ 3.1

## 環境チェック

```
# Symfony環境チェック
$ php bin/symfony_requirements
# Symfonyバージョン確認
$ php bin/console --version
# Symfonyコンソールコマンドリスト
$ php bin/console list
# Symfonyサービスコンテナリスト
$ php bin/console debug:container
# ルーティングリスト
$ php bin/console debug:router
```

## デバッグ

```
* Dumps the current configuration for an extension
$ php bin/consle debug:config
* Displays current services for an application
$ php bin/consle debug:container
* Displays configured listeners for an application
$ php bin/consle debug:event-dispatcher
* Displays current routes for an application
$ php bin/consle debug:router
* Displays current mailers for an application
$ php bin/consle debug:swiftmailer
* Displays translation messages information
$ php bin/consle debug:translation
* Shows a list of twig functions, filters, globals and tests
$ php bin/consle debug:twig
```

## バンドル

```
# バンドル作成
$ php bin/console generate:bundle --namespace=AppBundle --format=annotation
```

## エンティティ

```
# データベース削除
$ php bin/console doctrine:database:drop --force
# データベース作成
$ php bin/console doctrine:database:create
# スキーマ（テーブル）削除
$ php bin/console doctrine:schema:drop --force
# スキーマ（テーブル）作成
$ php bin/console doctrine:schema:create
# テストデータ投入
$ php bin/console doctrine:fixtures:load
# エンティティ作成
$ php bin/console generate:doctrine:entity --entity=AppBundle:Post
# ゲッターセッター生成
$ php bin/console doctrine:generate:entities AppBundle
# エンティティからテーブルスキーマの作成
$ php bin/console doctrine:schema:update --force
# エンティティからCRUD生成
$ php bin/console doctrine:generate:crud --entity=AppBundle:Post --with-write
* DQLをコマンドラインで直接実行
$ php bin/console doctrine:query:dql "SELECT p FROM AppBundle:Post p"
$ php bin/console doctrine:query:dql "SELECT p FROM AppBundle:Post p"
* SQLをコマンドラインで直接実行
$ php bin/console doctrine:query:sql "SELECT * FROM posts"
```

## ジェネレータ

* バンドル作成(src/AppBundle)
$ php bin/console generate:bundle --namespace=AppBundle
* コンソールコマンド作成(src/AppBundle/Command/BlogPublishPostsCommand.php)
$ php bin/console generate:command AppBundle blog:publish-posts
* 作成したコンソールコマンド`blog:publish-posts`を実行する
$ php bin/console blog:publish-posts
Command result.
* コントローラ作成(src/AppBundle/Controller/PostController.php)
$ php bin/console generate:controller --controller=AppBundle:Post
* Generates a CRUD based on a Doctrine entity
$ php bin/console generate:doctrine:crud --entity=AppBundle:Post --with-write
* Generates entity classes and method stubs from your mapping information
$ php bin/console generate:doctrine:entities
* Generates a new Doctrine entity inside a bundle
$ php bin/console generate:doctrine:entity
* Generates a form type class based on a Doctrine entity
$ php bin/console generate:doctrine:form

## キャッシュ, Assets

```
# キャッシュクリア(dev)
php bin/console assets:install --symlink
php bin/console cache:clear --no-warmup
# キャッシュクリア(prod)
php bin/console assets:install --symlink
php bin/console cache:clear --env=prod --no-debug

```

## FOSUserBundleコマンド

```
# アクティブなユーザーかチェックする
$ php bin/console fos:user:activate
# ユーザーのパスワードを変更する
$ php bin/console fos:user:change-password
# アクティブなユーザーを作成する
$ php bin/console fos:user:create
# Deactivate a user
$ php bin/console fos:user:deactivate
# Demote a user by removing a role
$ php bin/console fos:user:demote
# Promotes a user by adding a role
$ php bin/console fos:user:promote
```

rm -rf var/cache/*
rm -rf var/logs/*
rm -rf var/sessions/*


sudo mkdir /var/lib/php/sessions
sudo chown -R root:apache /var/lib/php/sessions
sudo chmod -R 777 /var/lib/php/sessions

framework:
    session:
        # http://symfony.com/doc/current/reference/configuration/framework.html#handler-id
        handler_id:  session.handler.native_file
        #save_path:   "%kernel.root_dir%/../var/sessions/%kernel.environment%"
        save_path:   "/var/lib/php/sessions"
