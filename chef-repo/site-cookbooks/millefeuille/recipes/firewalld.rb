#
# Cookbook Name:: millefeuille
# Recipe:: firewalld
#
# Copyright 2016, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
log "firewalld"

package "firewalld" do
  action :install
end

service "firewalld" do
  action    [ :enable, :start ]
  supports  :status => true, :restart => true, :reload => true
end

execute "firewalld_port" do
  command <<-EOH
    firewall-cmd --add-service=ssh --zone=public --permanent
    firewall-cmd --add-service=http --zone=public --permanent
    firewall-cmd --add-service=https --zone=public --permanent
  EOH
  notifies :restart, "service[firewalld]"
end
