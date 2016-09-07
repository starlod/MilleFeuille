#
# Cookbook Name:: millefeuille
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "Hello World!"
include_recipe 'yum::default'
include_recipe 'yum-remi::default'

%w[
  gd-last
  t1lib
].each do |pkg|
    package "#{pkg}" do
        action [ :install ]
        options "--enablerepo=remi"
    end
end

%w[
  php
  php-cli
  php-common
  php-gd
  php-xml
  php-pdo
  php-mbstring
  php-mysqlnd
  php-opcache
].each do |pkg|
  package "#{pkg}" do
    action :install
    options '--enablerepo=remi-php71'
  end
end

execute "composer-install" do
  command "curl -sS https://getcomposer.org/installer | php ;mv composer.phar /usr/local/bin/composer"
  not_if { ::File.exists?("/usr/local/bin/composer")}
end

execute "symfony-install" do
  command "curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony;chmod a+x /usr/local/bin/symfony"
  not_if { ::File.exists?("/usr/local/bin/symfony")}
end


# package  do
#   action :install
#   options "--enablerepo=remi --enablerepo=remi-php71"
# end

# # php インストール
# %w[php php-pecl-apcu php-cli php-devel php-common php-mbstring php-mysqlnd php-fpm php-gd php-gmp php-mcrypt php-opcache php-pdo php-xml php-intl php-pear].each do |p|
#   package p do
#     action :install
#     options "--enablerepo=remi --enablerepo=remi-php71"
#   end
# end

# template "php.ini" do
#   path "/etc/php.ini"
#   source "php.ini.erb"
#   mode 0644
# end

# mysql_service 'default' do
#   version '5.6'
#   initial_root_password 'password'
#   action [:create, :start]
# end

#package mariadb-libs do
#  action :remove
#end

remote_file "#{Chef::Config[:file_cache_path]}/mysql-community-release-el7-5.noarch.rpm" do
  source 'http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm'
  action :create
end

rpm_package 'mysql-community-release' do
  source "#{Chef::Config[:file_cache_path]}/mysql-community-release-el7-5.noarch.rpm"
  action :install
end

package 'mysql-server' do
  action :install
end

service 'mysqld' do
  action [:enable, :start]
end