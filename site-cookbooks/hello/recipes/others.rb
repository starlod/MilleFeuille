#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# others
packages = %w(git unzip fontconfig-devel)
packages.each do |pkg|
  package pkg do
    action :install
  end
end
