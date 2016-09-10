#
# Cookbook Name:: node
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "Hello World!"

yum_package "yum-fastestmirror" do
  action :install
end

execute "yum-update" do
  user "root"
  command "yum -y update"
  action :run
end

# 日本語関連パッケージ
%W{ ibus-kkc vlgothic-fonts vlgothic-p-fonts }.each do |pkg|
  package "#{pkg}" do
    action [ :install, :upgrade ]
  end
end

execute "lang-japanese" do
  user "root"
  command  <<-EOH
    localectl set-locale LANG=ja_JP.UTF-8
    localectl set-locale LANG=ja_JP.UTF-8
  EOH
  action :run
end

# 開発用ツール
execute "devtools" do
  user "root"
  command <<-EOH
    yum -y groupinstall "Development Tools"
    yum -y install kernel-devel kernel-headers
  EOH
  action :run
end

%W{ zip unzip lsof pcre pcre-devel openssl openssl-devel }.each do |pkg|
  package "#{pkg}" do
    action [ :install, :upgrade ]
  end
end

directory "/var/lib/php/sessions" do
  owner "vagrant"
  group "vagrant"
  recursive true
  mode 0775
  action :create_if_missing
end
