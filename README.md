php-zenoss-api
==========

Zenoss XMLRPC API PHP Library

For quick and easy integration of Zenoss Network Monitoring <http://www.zenoss.com>.

 * @category   PHP Library
 * @package    Zenoss
 * @author     Benton Snyder <introspectr3@gmail.com>
 * @copyright  2013 Noumenal Designs
 * @license    GPLv3
 * @website    Noumenal Designs <http://www.noumenaldesigns.com>

Tested on Zenoss 3.2, 4.2

<h4>Usage</h4>

 require('zenoss.php');<br />
 $zenoss = new Zenoss('192.168.1.10', 'admin', 'password');<br />
 $devices = $zenoss->getDevices();<br />
