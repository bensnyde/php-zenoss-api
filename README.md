php-zenoss
==========

<h3>PHP XMLRPC wrapper for Zenoss by Benton Snyder</h3>

<p>For quick and easy integration of Zenoss Network Monitoring into your applications.</p>

<p>Tested on Zenoss v3.2</p>

<h4>TO DO</h4>
<ul>
 <li>Validate $deviceURI + $interface parameters</li>
 <li>The fetch Zenoss RRD image function leaves orphaned graphics in the $tmp directory to prevent concurrent fetch request issues.</li>
 <li>Add exception error handling and verbosity to zQuery method</li>
</ul>

<h4>Usage</h4>

 require('zenoss.php');<br />
 $zenoss = new Zenoss('192.168.1.10', 'admin', 'password');<br />
 var_dump($zenoss->getDevices());<br />
