#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# SELinux無効化
bash 'selinux' do
  user 'root'
  code <<-EOC
    sed -i 's/SELINUX=enforcing/SELINUX=permissive/' /etc/selinux/config
  EOC
end

# others
packages = %w(git unzip fontconfig-devel)
packages.each do |pkg|
  package pkg do
    action :install
  end
end
