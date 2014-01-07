# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "precise32"

    config.vm.box_url = "http://files.vagrantup.com/precise32.box"

    config.vm.network "private_network", ip: "192.168.50.4"

    config.vm.network :forwarded_port, guest: 80, host: 8080
    config.vm.network :forwarded_port, guest: 3306, host: 7777


    config.vm.provision :shell, :path => "vagrant_install.sh"

    config.vm.synced_folder "./" , "/var/www/devsave/",
        owner: "www-data", group: "www-data"

    config.vm.provider "virtualbox" do |v|
      v.name = "devsave_vagrant"
      v.memory = 512
    end

    # If true, then any SSH connections made will enable agent forwarding.
    # Default value: false
    # config.ssh.forward_agent = true

    # Share an additional folder to the guest VM. The first argument is
    # the path on the host to the actual folder. The second argument is
    # the path on the guest to mount the folder. And the optional third
    # argument is a set of non-required options.
    # config.vm.synced_folder "../data", "/vagrant_data"
end