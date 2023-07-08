#!/bin/sh
# This file will only be used by the main server.
# 1. Replace the text "main_ip" by the main server's ip
# 2. ssh into the target server
# 3. make sure to login as root
# 4. run the file 

apt update \
&& apt install tigervnc-standalone-server tigervnc-xorg-extension tigervnc-viewer -y \
&& apt install ubuntu-gnome-desktop -y \
&& systemctl enable gdm \
&& systemctl start gdm \
&& apt install nginx -y \
&& ufw allow 80/tcp \
&& ufw allow 81 \
&& git clone https://github.com/marifuli/private-cloud-browser-project.git \
&& cd private-cloud-browser-project

gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-ac-timeout '0' && gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-battery-timeout '0'

myuser="root"
mypasswd="12345678"
mkdir /$myuser/.vnc
echo $mypasswd | vncpasswd -f > /$myuser/.vnc/passwd
chown -R $myuser:$myuser /$myuser/.vnc
chmod 0600 /$myuser/.vnc/passwd

sleep 5
vncserver
vncserver -kill :1
vncserver
export DISPLAY=:1 && firefox --no-sandbox --start-fullscreen --kiosk https://login.yahoo.com & 

echo "localhost" > main_ip.txt
./novnc/utils/novnc_proxy --vnc localhost:5901 --listen 81 &