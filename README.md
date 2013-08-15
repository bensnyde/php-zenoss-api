php-zenoss-api
==========

Zenoss XMLRPC API PHP Library

For quick and easy integration of Zenoss Network Monitoring <http://www.zenoss.com>.

@filename zenoss.php
@author Benton Snyder <introspectr3@gmail.com>
@link <http://noumenaldesigns.com>

Tested on Zenoss 3.2, 4.2

<h4>Usage</h4>

 require('zenoss.php');<br />
 $zenoss = new Zenoss('192.168.1.10', 'admin', 'password');<br />
 $devices = $zenoss->getDevices();<br />
