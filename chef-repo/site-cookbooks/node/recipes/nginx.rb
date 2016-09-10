#
# Cookbook Name:: node
# Recipe:: nginx
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "nginx install"

include_recipe 'yum::default'
include_recipe 'yum-epel::default'

%W{ httpd httpd-tools }.each do |pkg|
  package "#{pkg}" do
    action [ :remove ]
  end
end

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

template "nginx.conf" do
  path "/etc/nginx/nginx.conf"
  owner "nginx"
  group "nginx"
  mode 0644

  notifies :reload, "service[nginx]"
end
