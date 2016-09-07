boxの追加
$ vagrant box add centos7 https://f0fff3908f081cb6461b407be80daf97f07ac418.googledrive.com/host/0BwtuV7VyVTSkUG1PM3pCeDJ4dVE/centos7.box
boxの確認
$ vagrant box list
boxの削除
$ vagrant box remove centos7 PROVIDER
boxのpackage化
$ vagrant box repackage centos7 PROVIDER
初期化
$ vagrant init [box-name] [box-url]
起動
$ vagrant up
状態確認
$ vagrant status
停止
$ vagrant halt
破棄
$ vagrant destroy
仮想マシンにログイン
$ vagrant ssh

$ vagrant ssh-config --host [host-name] >> ~/.ssh/config
$ ssh [host-name]

$ VBoxManage -v
5.1.0r108711

$ vagrant -v
Vagrant 1.8.1

$ vagrant plugin install vagrant-omnibus
$ vagrant plugin install vagrant-vbguest
$ vagrant plugin install vagrant-vbox-snapshot

Chef, kinife-soloのインストール

$ gem i chef --no-ri --no-rdoc


$ knife solo init chef-repo
$ knife solo prepare centos7
$ knife solo cook centos7

$ cd ~/Projects/dev
$ chef exec knife solo init chef-repo
$ cd chef-repo
$ vagrant init centos/7
$ chef exec vagrant up
$ chef exec vagrant ssh-config --host dev >> ~/.ssh/config
$ chef exec knife solo prepare dev
$ chef exec knife cookbook create millefeuille -o site-cookbooks/
$ chef exec knife solo cook centos7

$ chef exec berks vendor cookbooks
$ chef exec vagrant provision

## cookbook

```
* 一覧
$ chef exec knife cookbook site list

* 名前を指定して検索
$ chef exec knife cookbook site search apache2

* クックブックの情報を見る
$ chef exec knife cookbook site show apache2

$ chef exec knife cookbook site download apache2
$ chef exec knife cookbook site install apache2

$ chef exec knife cookbook site download git
$ chef exec knife cookbook site install git

```