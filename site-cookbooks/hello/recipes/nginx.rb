#
# Cookbook Name:: nginx
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# nginx
package "nginx" do
  flush_cache [:before]
  action :install
  options "--enablerepo=remi"
end

service "nginx" do
  action [:start, :enable]
end

service "php-fpm" do
  action [:start, :enable]
end

template "nginx.conf" do
  path "/etc/nginx/nginx.conf"
  source "nginx.conf.erb"
  mode 0644
  notifies :restart, "service[nginx]", :delayed
end

template "www.conf" do
  path "/etc/php-fpm.d/www.conf"
  source "www.conf.erb"
  mode 0644
  notifies :restart, "service[php-fpm]", :delayed
end
