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
include_recipe 'yum-remi::default'

%W{ redis php-pecl-redis }.each do |pkg|
  package "#{pkg}" do
    action :install
    options "--enablerepo=remi,epel,remi-php71"
  end
end

service "redis" do
  action [:enable, :start]
end

service "nginx" do
  action :restart
end
