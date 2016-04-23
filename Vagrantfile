# encoding: utf-8
# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'yaml'

# Load settings from vagrant.yml or vagrant.yml.dist
current_dir = File.dirname(File.expand_path(__FILE__))
if File.file?("#{current_dir}/vagrant.yml")
    config_file = YAML.load_file("#{current_dir}/vagrant.yml")
else 
    config_file = YAML.load_file("#{current_dir}/vagrant.yml.dist")
end
settings    = config_file['configs'][config_file['configs']['use']]

Vagrant.configure(2) do |config|
    config.vm.box = settings['box_name']
    #config.vm.box_url = settings['box_url']
    #config.ssh.private_key_path = "~/.ssh/authorized_keys"
    # config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "private_network", ip: settings['vm_ip']

    # Create a public network, which generally matched to bridged network.
    # Bridged networks make the machine appear as another physical device on
    # your network.
    # config.vm.network "public_network"

    config.vm.synced_folder ".", settings['vm_project_path'], create: true, owner: "vagrant", group: "www-data", mount_options: ["dmode=775", "fmode=774"]

    config.vm.provider "virtualbox" do |vb|
        vb.name = settings['vm_name']
        vb.memory = settings['vm_memory']
        vb.cpus = settings['vm_cpus']
    end

    # Shell provisioning
    config.vm.provision "shell" do |s|
        s.path = "bin/provisioning.sh"
        s.args = [ settings['vm_project_path'], settings['vm_db_name'], settings['vm_db_user'] ]
        s.privileged = true
    end
end
