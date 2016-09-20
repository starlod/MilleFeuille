## 環境

sudo usermod -aG starlod nginx
sudo usermod -aG nginx starlod

## 初回のみ

cd /var/www/html
sudo rm -rf MilleFeuille
git clone https://github.com/starlod/MilleFeuille

## リリース

### リポジトリを最新の状態にする

cd /var/www/html/MilleFeuille
git reset --hard

### ベンダーの更新

cd /var/www/html/MilleFeuille/symfony
composer install --ignore-platform-reqs
composer dump-autoload -o
npm install
gulp
rm -rf var/cache/*

### テストデータ投入

php bin/console doctrine:schema:update --force
php bin/console h:d:f:l
