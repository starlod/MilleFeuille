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

# RPMForgeリポジトリ追加
# bash 'add_rpmforge' do
#   user 'root'
#   code <<-EOC
#     rpm --import http://apt.sw.be/RPM-GPG-KEY.dag.txt
#     rpm -ivh http://apt.sw.be/redhat/el7/en/x86_64/rpmforge/RPMS/rpmforge-release-0.5.3-1.el7.rf.x86_64.rpm
#     sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/rpmforge.repo
#   EOC
#   creates "/etc/yum.repos.d/rpmforge.repo"
# end

yum_package "yum-fastestmirror" do
  action :install
end

execute "yum-update" do
  user "root"
  command "yum -y update"
  action :run
end
