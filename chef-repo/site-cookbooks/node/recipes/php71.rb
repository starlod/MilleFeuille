#
# Cookbook Name:: node
# Recipe:: php71
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "php install"

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
  php-pecl-apcu
  php-devel
  php-fpm
  php-gmp
  php-intl
  php-pear
].each do |pkg|
  package "#{pkg}" do
    action :install
    options '--enablerepo=remi-php71'
  end
end

service "php-fpm" do
  action [:enable, :start]
end

template "www.conf" do
  path "/etc/php-fpm.d/www.conf"
  owner "root"
  group "root"
  mode 0644

  notifies :restart, "service[php-fpm]"
end

execute "composer-install" do
  command "curl -sS https://getcomposer.org/installer | php ;mv composer.phar /usr/local/bin/composer"
  not_if { ::File.exists?("/usr/local/bin/composer")}
end

execute "symfony-install" do
  command "curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony;chmod a+x /usr/local/bin/symfony"
  not_if { ::File.exists?("/usr/local/bin/symfony")}
end

template "php.ini" do
  path "/etc/php.ini"
  source "php.ini.erb"
  mode 0644
end
