#!/bin/sh
# This file will only be used by the main server.
# 1. Replace the text "main_ip" by the main server's ip
# 2. ssh into the target server
# 3. make sure to login as root
# 4. run the file 

sleep 3
sudo sh -c 'echo root:whattheFuxk1231 | chpasswd'

cd /root \
&& apt update \
&& apt install tigervnc-standalone-server tigervnc-xorg-extension tigervnc-viewer -y \
&& ufw allow 80/tcp \
&& ufw allow 81 \
&& git clone https://github.com/marifuli/private-cloud-browser-project.git 

cd /root/private-cloud-browser-project

export DEBIAN_FRONTEND=noninteractive
apt-get install xfce4-session xfce4-goodies -y

apt install firefox -y

myuser="root"
mypasswd="12345678"
mkdir /$myuser/.vnc
echo $mypasswd | vncpasswd -f > /$myuser/.vnc/passwd
chown -R $myuser:$myuser /$myuser/.vnc
chmod 0600 /$myuser/.vnc/passwd

cat xstartup > ~/.vnc/xstartup
chmod +x ~/.vnc/xstartup

apt install curl -y
curl -sL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sleep 2
cd /root/private-cloud-browser-project/firefox-extension && npm i
apt install nginx -y
