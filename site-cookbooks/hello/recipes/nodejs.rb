#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

include_recipe "nodejs"

# TypeScript
bash 'typescript' do
  user 'root'
  code <<-EOC
    npm install -g typescript
    npm install -g gulp
  EOC
end
