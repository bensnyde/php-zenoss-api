php-zenoss
==========

PHP XMLRPC wrapper for Zenoss by Benton Snyder

For quick and easy integration of Zenoss Network Monitoring into your applications. 

Tested on Zenoss v3.2

TO DO:
-Validate $deviceURI + $interface parameters
-The fetch Zenoss RRD image function leaves orphaned graphics in the $tmp directory to prevent concurrent fetch request issues. 
-Add exception error handling and verbosity to zQuery method

Usage:

require('zenoss.php');
$zenoss = new Zenoss('192.168.1.10', 'admin', 'password');
var_dump($zenoss->getDevices());
