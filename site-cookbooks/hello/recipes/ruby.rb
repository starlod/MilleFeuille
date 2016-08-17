#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

include_recipe "rbenv"
include_recipe "ruby_build"

# rbenv_ruby node["ruby"]["version"] do
#   ruby_version node["ruby"]["version"]
#   global true
# end

node['ruby']['versions'].each { |v|
  # 指定バージョンのrubyをインストール
  rbenv_ruby v['version'] do
    ruby_version v['version']
    global true if v["global"]
  end

  # bunldleを設定する。
  rbenv_gem "bundler" do
    ruby_version v['version']
  end
}
