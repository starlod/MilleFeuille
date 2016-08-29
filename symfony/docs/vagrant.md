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
