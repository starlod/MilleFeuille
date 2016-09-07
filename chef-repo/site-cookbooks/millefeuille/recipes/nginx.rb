#
# Cookbook Name:: millefeuille
# Recipe:: nginx
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "nginx install"

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
