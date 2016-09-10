# 初期rootパスワード
sudo grep 'A temporary password is generated for' /var/log/mysqld.log

# 初期設定

mysql_secure_installation

# 文字コード設定確認

show variables like "chara%";

## データベースの作成＆ユーザーの作成

DROP DATABASE IF EXISTS symfony;
CREATE DATABASE symfony;
grant all privileges on symfony.* to dbuser@"%" identified by 'Network7932!';
show create database symfony;
