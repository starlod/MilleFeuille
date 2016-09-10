#
# Cookbook Name:: node
# Recipe:: nodejs
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "Nodejs"

include_recipe 'nodejs::default'

nodejs_npm "npm"

# スクレイピング
nodejs_npm "cheerio-httpcli"

# キャプチャ
nodejs_npm "phantomjs"
nodejs_npm "casperjs"

# タスクランナー
nodejs_npm "gulp"

# 文字コード自動判別
nodejs_npm "iconv"
nodejs_npm "jschardet"
nodejs_npm "iconv-lite"

# Excel操作
nodejs_npm 'officegen'

# ツイート検索
# nodejs_npm 'twit'

# Facebook API
nodejs_npm 'fb'

# Amazon API
nodejs_npm 'apac'

# Flickr API
nodejs_npm 'node-flickr'

# YouTube API
nodejs_npm 'youtube-node'

# スパム対策
nodejs_npm 'bayes'

# メーラー
# nodejs_npm 'twit'

# TypeScript
nodejs_npm "typescript"
nodejs_npm "typings"
nodejs_npm 'dtsm'

execute "typings" do
  command "typings install dt~node --global"
end

# for PhantomJS & CasperJS
%w{freetype fontconfig}.each do |pkg|
  package pkg do
    action :install
  end
end

%w{gdal wget curl}.each do |pkg|
  package pkg do
    action :install
  end
end
