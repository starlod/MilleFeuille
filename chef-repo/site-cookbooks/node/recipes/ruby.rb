#
# Cookbook Name:: node
# Recipe:: ruby
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "Ruby Install"

include_recipe "rbenv"

rbenv_ruby "2.3.1" do
  ruby_version "2.3.1"
  global true
end
