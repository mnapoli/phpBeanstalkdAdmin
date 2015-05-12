Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.network "forwarded_port", guest: 10000, host: 10000

  config.vm.provision :shell, inline: "sudo apt-get install -qq -y php5 beanstalkd"
  config.vm.provision :shell, inline: "sudo service beanstalkd start", run: "always"
end
