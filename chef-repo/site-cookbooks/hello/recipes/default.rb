#
# Cookbook Name:: hello
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# EPELリポジトリ追加
package 'epel-release.noarch' do
  action :install
end

# Remiリポジトリ追加
bash 'add_remi' do
  user 'root'
  code <<-EOC
    rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
    sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/remi.repo
  EOC
  creates "/etc/yum.repos.d/remi.repo"
end

bash 'add_rpmforge' do
  user 'root'
  code <<-EOC
    rpm --import http://apt.sw.be/RPM-GPG-KEY.dag.txt
    rpm -ivh http://apt.sw.be/redhat/el7/en/x86_64/rpmforge/RPMS/rpmforge-release-0.5.3-1.el7.rf.x86_64.rpm
    sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/rpmforge.repo
  EOC
  creates "/etc/yum.repos.d/rpmforge.repo"
end

# SELinux無効化
bash 'selinux' do
  user 'root'
  code <<-EOC
    sed -i 's/SELINUX=enforcing/SELINUX=disabled/' /etc/selinux/config
    setenforce 0
  EOC
end

yum_package "yum-fastestmirror" do
  action :install
end

execute "yum-update" do
  user "root"
  command "yum -y update"
  action :run
end

package 'git' do
  action :install
end

# php
package 'php' do
  flush_cache [:before]
  action :install
  options "--enablerepo=remi --enablerepo=remi-php71"
end

%w(php-openssl php-common php-mbstring php-xml).each do |pkg|
  package pkg do
    action :install
    options "--enablerepo=remi --enablerepo=remi-php71"
  end
end

# httpd
package 'httpd' do
  action :install
end

template "index.html" do
  path "/var/www/html/index.html"
  source "index.html.erb"
  mode 644
end

template "httpd.conf" do
  path "/etc/httpd/conf/httpd.conf"
  source "httpd.conf.erb"
  mode 0644
end

template "virtual.conf" do
  path "/etc/httpd/conf.d/virtual.conf"
  source "virtual.conf.erb"
  mode 0644
end

service "httpd" do
  action [:restart, :enable]
end

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
  action [:enable, :start]
end

service 'firewalld' do
  action [:stop, :disable]
end

# nodejs
%w(nodejs npm).each do |pkg|
  package pkg do
    action :install
    options "--enablerepo=epel"
  end
end

# TypeScript
bash 'typescript' do
  user 'root'
  code <<-EOC
    npm install -g typescript
    npm install -g gulp
  EOC
end

# execute "phpunit-install" do
#   command "pear config-set auto_discover 1; pear install pear.phpunit.de/PHPUnit"
#   not_if { ::File.exists?("/usr/bin/phpunit")}
# end

execute "composer-install" do
  command "curl -sS https://getcomposer.org/installer | php ;mv composer.phar /usr/local/bin/composer"
  not_if { ::File.exists?("/usr/local/bin/composer")}
end

# firewall 'default'

# firewall 'default' do
#     action :install
# end

# firewall_rule 'ssh' do
#   port     2200
#   command  :allow
# end

# firewall_rule 'http' do
#     port     80
#     protocol :tcp
#     position 1
#     command   :allow
# end

# others
packages = %w(unzip fontconfig-devel)
packages.each do |pkg|
  package pkg do
    action :install
  end
end
