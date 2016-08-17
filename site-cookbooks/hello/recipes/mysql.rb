#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# include_recipe "yum-mysql-community::mysql57-community-dmr"
# include_recipe "mysql::server"
# include_recipe "mysql::client"
#
# mysql_service 'default' do
#   version '5.7'
#   bind_address '0.0.0.0'
#   port '3306'
#   data_dir '/data'
#   initial_root_password node[:mysql][:root][:password]
#   action [:create, :start]
# end
#
# mysql_client 'default' do
#   action :create
# end


# MySQL
remote_file "#{Chef::Config[:file_cache_path]}/mysql57-community-release-el7-7.noarch.rpm" do
  source 'http://repo.mysql.com/mysql57-community-release-el7-7.noarch.rpm'
  action :create
end

rpm_package 'mysql-community-release' do
  source "#{Chef::Config[:file_cache_path]}/mysql57-community-release-el7-7.noarch.rpm"
  action :install
end

package 'mysql-server' do
  action :install
end

service 'mysqld' do
  action [:start, :enable]
end

template "my.cnf" do
  path "/etc/my.cnf"
  source "my.cnf.erb"
  mode 0644
  notifies :restart, "service[mysqld]", :delayed
end

# root_password = node[:mysql][:root][:password]
# # MySQLにパスワード入力なしでログインできるよう設定
# bash 'mysql_secure_installation1' do
#   user "root"
#   code <<-"EOH"
#     if [ `grep "skip-grant-tables" /etc/my.cnf | wc -l` == "0" ]; then echo "skip-grant-tables" >> /etc/my.cnf; fi
#   EOH
#   action :run
# end
#
# service 'mysqld' do
#   action [:restart]
# end
#
# # MySQL初期設定
# bash 'mysql_secure_installation2' do
#   user 'root'
#   code <<-"EOH"
#     /usr/bin/mysql -e "drop database if exists test;"
#     /usr/bin/mysql -e "delete from user where user = '';" -D mysql
#     /usr/bin/mysql -e "SET PASSWORD FOR 'root'@'::1' = PASSWORD(\'#{root_password}\');" -D mysql
#     /usr/bin/mysql -e "SET PASSWORD FOR 'root'@'127.0.0.1' = PASSWORD(\'#{root_password}\');" -D mysql
#     /usr/bin/mysql -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD(\'#{root_password}\');" -D mysql
#     /usr/bin/mysql -e "FLUSH PRIVILEGES;" -D mysql
#   EOH
#   action :run
# end
#
# # MySQLにパスワード入力なしでログインできるよう設定を元に戻す
# bash 'mysql_secure_installation3' do
#   user "root"
#   code <<-"EOH"
#     sed -i 's/skip-grant-tables//' /etc/my.cnf
#   EOH
#   action :run
# end
#
# service 'mysqld' do
#   action [:restart]
# end
