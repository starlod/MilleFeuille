#
# Cookbook Name:: node
# Recipe:: redis
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "redis install"

include_recipe 'yum::default'
include_recipe 'yum-epel::default'

package "nginx" do
  action :install
  options "--enablerepo=epel"
end

service "nginx" do
  action [:enable, :start]
end

group 'vagrant' do
  action :modify
  members 'nginx'
  append true
end

# template "redis.conf" do
#   owner "root"
#   group "root"
#   mode 0644

#   notifies :restart, "service[redis]"
# end

# cp /etc/nginx/nginx.conf /var/www/html/symfony