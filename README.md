php-zenoss-api
==========

<h3>PHP wrapper for Zenoss XMLRPC API</h3>

<p>For quick and easy integration of <a href="http://www.zenoss.com" alt="Zenoss">Zenoss Network Monitoring</a> into your applications.</p>

<p>@filename zenoss.php<br />@author Benton Snyder<br />@link <a href="http://noumenaldesigns.com" alt="Noumenal Designs">http://noumenaldesigns.com</a></p>

<p>Tested on Zenoss 3.2, 4.2</p>

<h4>TO DO</h4>
<ul>
 <li>Validate $deviceURI + $interface parameters</li>
 <li>fetchZenossGraphImage() leaves orphaned graphics in the $tmp directory to prevent concurrent fetch request issues.</li>
 <li>Add exception error handling and verbosity to zQuery()</li>
</ul>

<h4>Usage</h4>

 require('zenoss.php');<br />
 $zenoss = new Zenoss('192.168.1.10', 'admin', 'password');<br />
 $devices = $zenoss->getDevices();<br />
