<?php 
function get_vnc_url($ip)
{
    return "http://$ip:81/vnc.html?host=$ip&port=81";
}