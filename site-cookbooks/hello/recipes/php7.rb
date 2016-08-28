#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# php
package 'php' do
  flush_cache [:before]
  action :install
  options "--enablerepo=remi --enablerepo=remi-php71"
end

%w(php-openssl php-devel php-common php-mbstring php-xml php-cli php-mysql php-pecl-xdebug php-fpm php-gd php-gmp php-mcrypt php-opcache php-pdo php-intl php-pear).each do |pkg|
  package pkg do
    action :install
    options "--enablerepo=remi --enablerepo=remi-php71"
  end
end

execute "phpunit-install" do
  command "pear config-set auto_discover 1; pear install pear.phpunit.de/PHPUnit"
  not_if { ::File.exists?("/usr/bin/phpunit")}
end

execute "composer-install" do
  command "curl -sS https://getcomposer.org/installer | php ;mv composer.phar /usr/local/bin/composer"
  not_if { ::File.exists?("/usr/local/bin/composer")}
end
