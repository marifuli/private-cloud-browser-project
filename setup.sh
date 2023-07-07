#!/bin/sh
# This file will only be used by the main server.
# 1. Replace the text "main_ip" by the main server's ip
# 2. ssh into the target server
# 3. make sure to login as root
# 4. run the file 

sudo apt update  
sudo apt install tigervnc-standalone-server tigervnc-xorg-extension tigervnc-viewer  
sudo apt install ubuntu-gnome-desktop
sudo systemctl enable gdm
sudo systemctl start gdm

# Make gnome wake all time  
sudo systemctl mask sleep.target suspend.target hibernate.target hybrid-sleep.target  

vncserver
vncserver -kill :1
vncserver
export DISPLAY=:1 #set display

git clone https://github.com/marifuli/private-cloud-browser-project.git
echo "main_ip" > main_ip.txt
cd private-cloud-browser-project/novnc
./utils/novnc_proxy --vnc localhost:5901 --listen 80