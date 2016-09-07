#
# Cookbook Name:: millefeuille
# Recipe:: mysql57
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "mysql install"

bash 'remove mariadb' do
  code <<-EOH
    yum remove mariadb*
  EOH
end

remote_file "#{Chef::Config[:file_cache_path]}/mysql57-community-release-el7-7.noarch.rpm" do
  source 'http://repo.mysql.com/mysql57-community-release-el7-7.noarch.rpm'
  action :create
end

package 'mysql-server' do
  action :install
  options "--enablerepo=mysql57-community-dmr"
end

service 'mysqld' do
  action [:enable, :start]
end
