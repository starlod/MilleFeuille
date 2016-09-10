#
# Cookbook Name:: node
# Recipe:: mysql57
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "MySQL57 install"

%W{ mariadb-libs }.each do |pkg|
  package "#{pkg}" do
    action :remove
  end
end

execute "mysql-community-release" do
  user "root"
  command "yum -y localinstall http://repo.mysql.com/mysql57-community-release-el7-8.noarch.rpm"
  action :run
end

package 'mysql-server' do
  action :install
  options '--enablerepo=mysql57-community'
end

service 'mysqld' do
  action [:enable, :start]
end

directory "/etc/mysql" do
  owner "root"
  group "root"
  mode 00755
  action :create
end

template "my.cnf" do
  path "/etc/my.cnf"
  owner "root"
  group "root"
  mode 0644

  notifies :restart, "service[mysqld]"
end
