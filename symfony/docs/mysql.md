sudo grep 'A temporary password is generated for' /var/log/mysqld.log

mysql -u root -pkj0aLJuuLZ_p

Network7932!

## アンインストール

sudo yum -y remove mariadb-libs
sudo yum -y remove mysql*
sudo rm -rf /var/lib/mysql/

## データベースの作成＆ユーザーの作成

CREATE DATABASE symfony;
grant all privileges on symfony.* to dbuser@127.0.0.1 identified by 'Network7932!';
