# さくらのVPS設定

sudo vim /etc/hosts

ik1-327-23903.vs.sakura.ne.jp
153.126.183.157
名前	ステージングサーバー
説明
ゾーン	石狩第1ゾーン
メモリ	1 GB
ストレージ	HDD 100 GB
CPU	2コア
オプション
契約状態	利用中
サービスコード	112700907100

## 初回接続

```
ssh root@153.126.183.157
```

## 日本語化

```
# echo $LANG
ja_JP.UTF-8
```

※さくらのVPSは元々日本語化されている

## 作業用ユーザーの作成

```

# useradd starlod
# passwd starlod
# usermod -G wheel starlod
## Allows people in group wheel to run all commands
%wheel  ALL=(ALL)       ALL
```

※さくらのVPSではwheelは既にコメント解除されていた。

## 公開鍵認証

```
# ssh starlod@153.126.183.157
# mkdir .ssh
# chmod 700 .ssh
```

```
cd ~/.ssh
ssh-keygen -t rsa -v
ls -la
-rw-------   1 yuki  staff  1679  8 26 21:30 sakura_vps
-rw-r--r--   1 yuki  staff   402  8 26 21:30 sakura_vps.pub
```

```
chmod 600 sakura_vps.pub
scp ~/.ssh/sakura_vps.pub starlod@153.126.183.157:~/.ssh/authorized_keys
ssh -i ~/.ssh/sakura_vps starlod@153.126.183.157
```

## SSH

- ポート番号の変更
- パスワードログインの禁止
- rootログインの禁止
- 脆弱性の多いSSHバージョン1を無効化

```
# cp /etc/ssh/sshd_config /etc/ssh/sshd_config.org
# vi /etc/ssh/sshd_config
Port 60022
Protocol 2
PasswordAuthentication no
PermitRootLogin yes

# systemctl restart sshd
```



## ファイアウォール

```
# yum -y install firewalld
# systemctl start firewalld
# systemctl enable firewalld
# firewall-cmd --permanent --zone=public --add-service=ssh
```

※さくらのVPSは既にfirewaldはインストール済みだったので上のコマンドは不要でした。

### HTTP, HTTPSを許可

```
# firewall-cmd --add-service=http --zone=public --permanent
# firewall-cmd --add-service=https --zone=public --permanent
```

### SSHポート変更

```
# cp /usr/lib/firewalld/services/ssh.xml /etc/firewalld/services/ssh.xml
# vi /etc/firewalld/services/ssh.xml
<?xml version="1.0" encoding="utf-8"?>
<service>
  <short>SSH</short>
  <description>Secure Shell (SSH) is a protocol for logging into and executing commands on remote machines. It provides secure encrypted communications. If you plan on accessing your machine remotely via SSH over a firewalled interface, enable this option. You need the openssh-server package installed for this option to be useful.</description>
  <port protocol="tcp" port="60022"/>
</service>
```

```
# systemctl restart firewalld
# firewall-cmd --list-all
public (default, active)
  interfaces: eth0
  sources:
  services: dhcpv6-client ssh
  ports:
  masquerade: no
  forward-ports:
  icmp-blocks:
  rich rules:
# ss -ant | grep 60022
```

```
# vi /etc/ssh/sshd_config
Host sakura_vps
 HostName 153.126.183.157
 Port 60022
 User starlod
 IdentityFile ~/.ssh/sakura_vps
```

## yum自動更新

```
# yum install -y yum-cron
# vi /etc/yum/yum-cron.conf
apply_updates = yes
update_cmd = security

# systemctl start yum-cron
# systemctl enable yum-cron
```

## SELinuxの無効化

```
* SELinux ステータス確認
# getenforce
Enforcing

* SELinux 無効化(一時的に無効にする)
# setenforce 0

* SELinux 無効化(起動時に無効にする)
# sed -i 's/SELINUX=enforcing/SELINUX=disabled/' /etc/selinux/config

* 再起動
# reboot

* SELinux ステータス確認
# getenforce
Disabled
```

## パッケージ、リポジトリのインストール及びアップデート

```
# yum -y update
# yum -y install yum-plugin-priorities
# yum -y groupinstall "Base" "Development tools" "Japanese Support"

* EPELリポジトリ追加
# yum -y install epel-release

* Remiリポジトリ追加
# rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

* RPMforgeリポジトリ追加
# yum -y install http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.3-1.el7.rf.x86_64.rpm
```

## Git

# yum -y install git git-daemon git-all
# git --version
git version 1.8.3.1

### Gitに必要なコンポーネントをインストール

# yum -y install curl-devel expat-devel gettext-devel openssl-devel zlib-devel perl-ExtUtils-MakeMaker

### Git用グループの追加とユーザをグループに追加

```
# groupadd gitgroup
# usermod -aG gitgroup starlod
# cat /etc/group

# mkdir /var/git
# mkdir /var/git/repository.git

# cd /var/git/repository.git/
# git init --bare --shared
Initialized empty shared Git repository in /var/git/repository.git/
# chown -R root:gitgroup /var/git

# git config --global user.name starlod
# git config --global user.email starlod.ggl@gmail.com

# git config --global --list
user.name=starlod
user.email=starlod.ggl@gmail.com
```

## Ruby

# yum -y install gcc-c++ git glibc-headers libffi-devel libxml2 libxml2-devel libxslt libxslt-devel libyaml-devel make nodejs npm openssl-devel readline readline-devel sqlite-devel zlib zlib-devel

$ git clone https://github.com/sstephenson/rbenv.git ~/.rbenv
$ git clone https://github.com/sstephenson/ruby-build.git ~/.rbenv/plugins/ruby-build
$ git clone https://github.com/sstephenson/rbenv-gem-rehash.git ~/.rbenv/plugins/rbenv-gem-rehash

$ echo 'export PATH="$HOME/.rbenv/plugins/ruby-build/bin:$PATH"' >> ~/.bashrc
$ echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.bashrc
$ echo 'eval "$(rbenv init -)"' >> ~/.bashrc
$ source ~/.bashrc

$ rbenv install --list
$ rbenv install 2.3.1
$ rbenv versions
$ rbenv global 2.3.1
$ ruby -v

$ gem update --system
$ gem update

## MySQL

# yum -y remove mariadb-libs
# yum -y remove mysql*
# rm -rf /var/lib/mysql/

# yum -y localinstall http://dev.mysql.com/get/mysql57-community-release-el7-7.noarch.rpm
# yum -y install mysql-community-server

# systemctl start mysqld
# systemctl enable mysqld

# mysql -V
mysql  Ver 14.14 Distrib 5.7.14, for Linux (x86_64) using  EditLine wrapper

# grep 'password' /var/log/mysqld.log
2016-08-26T13:49:50.803932Z 1 [Note] A temporary password is generated for root@localhost: /EnwDARtV60*

# cp /etc/my.cnf /etc/my.cnf.org
# vi /etc/my.cnf
character-set-server = utf8
default_password_lifetime = 0

# systemctl restart mysqld

## PHP

# yum -y remove php*
# yum -y --enablerepo=remi-php71 install php php-pecl-apcu php-cli php-devel php-common php-mbstring php-mysqlnd php-fpm php-gd php-gmp php-opcache php-pdo php-xml php-intl php-pear
# php -v
PHP 7.1.0beta2 (cli) (built: Aug  3 2016 11:59:34) ( NTS )
Copyright (c) 1997-2016 The PHP Group
Zend Engine v3.1.0-dev, Copyright (c) 1998-2016 Zend Technologies
    with Zend OPcache v7.1.0beta2, Copyright (c) 1999-2016, by Zend Technologies
    with Xdebug v2.5.0-dev, Copyright (c) 2002-2016, by Derick Rethans

# cp /etc/php.ini /etc/php.ini.org
# vi /etc/php.ini
; PHP最大実行時間（秒）
max_execution_time = 300
; メモリ上限
memory_limit = 256M
; エラー表示の種類
error_reporting = E_ALL
; エラー表示の許可
display_errors = On
; エラーログの保存
log_errors = On
; ファイル操作のパフォーマンス向上（PHP5.1以降）
realpath_cache_size = 2M
; デフォルトの文字コード
default_charset = "UTF-8"
; タイムゾーンの設定
date.timezone = "Asia/Tokyo"
; デフォルト言語の設定
mbstring.language = Japanese

$ curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
$ sudo chmod a+x /usr/local/bin/composer
$ sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
$ sudo chmod a+x /usr/local/bin/symfony

## nginx

# yum --enablerepo=epel -y install nginx
# nginx -v
nginx version: nginx/1.6.3

# vi /etc/nginx/nginx.conf
server_name mille-feuille-lab.net;

# systemctl start nginx
# systemctl enable nginx

### PHP-FPM

# vi /etc/php-fpm.d/www.conf
user = nginx
group = nginx

# systemctl start php-fpm
# systemctl enable php-fpm

# vi /etc/nginx/nginx.conf
    location ~ .php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param  PATH_INFO $fastcgi_path_info;
        include        fastcgi_params;
    }


# echo "<?php phpinfo() ?>" > /usr/share/nginx/html/info.php
# usermod -aG starlod nginx
# chown -R starlod:nginx /usr/share/nginx/html

## Nodejs

sudo yum -y install nodejs npm
sudo npm i -g cheerio-httpcli
sudo npm i -g phantomjs
sudo npm i -g casperjs
sudo npm i -g gulp
sudo npm i -g iconv
sudo npm i -g jschardet
sudo npm i -g iconv-lite
sudo npm i -g officegen
sudo npm i -g twit
sudo npm i -g fb
sudo npm i -g apac
sudo npm i -g node-flickr
sudo npm i -g youtube-node
sudo npm i -g bayes
sudo npm i -g typescript
sudo npm i -g typings
sudo npm i -g dtsm
