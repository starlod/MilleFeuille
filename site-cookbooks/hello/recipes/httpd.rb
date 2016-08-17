#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# httpd
package 'httpd' do
  action :install
end

service "httpd" do
  action [:start, :enable]
end

template "httpd.conf" do
  path "/etc/httpd/conf/httpd.conf"
  source "httpd.conf.erb"
  mode 0644
  notifies :restart, "service[httpd]", :delayed
end

template "vhosts.conf" do
  path "/etc/httpd/conf.d/vhosts.conf"
  source "vhosts.conf.erb"
  mode 0644
  notifies :restart, "service[httpd]", :delayed
end
