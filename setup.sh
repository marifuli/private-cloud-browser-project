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

cat acpi-support > /etc/default/acpi-support

myuser="root"
mypasswd="12345678"
mkdir /home/$myuser/.vnc
echo $mypasswd | vncpasswd -f > /home/$myuser/.vnc/passwd
chown -R $myuser:$myuser /home/$myuser/.vnc
chmod 0600 /home/$myuser/.vnc/passwd

vncserver
vncserver -kill :1
vncserver
export DISPLAY=:1 #set display

echo "localhost" > main_ip.txt
cd novnc
./utils/novnc_proxy --vnc localhost:5901 --listen 81 &