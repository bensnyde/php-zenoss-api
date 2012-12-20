<?php

class Zenoss
{
        private $tmp;
        private $protocol;
        private $address;
        private $port;
        private $username;
        private $password;
        private $cookie;

        /**
        * Public constructor
        *
        * @access       public
        * @param
        * @return
        */
        function __construct($address,$username,$password,$port='8080',$tmp='/tmp/',$protocol='http')
        {
                parent::__construct();
                $this->address = $address;
                $this->username = $username;
                $this->password = $password;
                $this->port = $port;
                $this->tmp = $tmp;
                $this->protocol = $protocol;
                $this->cookie = $tmp."zenoss_cookie.txt";
        }

        /**
         * Queries Zenoss for requested data
         *
         * @access      private
         * @param       array, string
         * @return      json
         */
        private function zQuery(array $data, $uri)
        {
                // fetch authorization cookie
                $ch = curl_init("{$this->protocol}://{$this->address}:{$this->port}/zport/acl_users/cookieAuthHelper/login");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                curl_exec($ch);

                // execute xmlrpc action
                curl_setopt($ch, CURLOPT_URL, "{$this->protocol}://{$this->address}:{$this->port}{$uri}");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $result = curl_exec($ch);

                curl_close($ch);

                return $result;
        }

        /**
         * Retrieves a listing of Zenoss Device Collectors
         *
         * @access      public
         * @param       string
         * @return      json
         */
        public function getDeviceCollectors($deviceURI)
        {
                $json_data = array();
                $json_main = array();

                $json_main['action'] = "DeviceRouter";
                $json_main['method'] = "getCollectors";
                $json_main['type'] = "rpc";
                $json_main['tid'] = 1;
                $json_main['data'] = $json_data;

                return $this->zQuery($json_main, $deviceURI.'/device_router');
        }

        /**
         * Retrieves listing of Zenoss events for specified device
         *
         * @access      public
         * @param       string, *int, *int, *string, *string
         * @return      json
         */
        public function getDeviceEvents($deviceURI, $start=0, $limit=100, $sort="severity", $dir="DESC")
        {
                // validation
                if(!is_int($start) || !is_int($limit))
                        return false;
                if($dir!="ASC" && $dir!="DESC")
                        return false;
                $validSort = array("Component, Count, Device, eventClass, firstTime, lastTime, severity, summary");
                if(!in_array($validSort, $sort))
                        return false;

                $json_params = array();
                $json_data = array();
                $json_main = array();

                $json_params['severity'] = array();
                $json_params['eventState'] = array();

                $json_data['start'] = $start;
                $json_data['limit'] = $limit;
                $json_data['dir'] = $dir;
                $json_data['sort'] = $sort;
                $json_data['params'] = $json_params;

                $json_main['action'] = "EventsRouter";
                $json_main['method'] = "query";
                $json_main['data'] = array($json_data);
                $json_main['tid'] = 1;
                $json_main['type'] = "rpc";

                return $this->zQuery($json_main, $deviceURI.'/evconsole_router');
        }

        /**
         * Retrieves listing of components for specified Zenoss Device
         *
         * @access      public
         * @param       string, *int, *int
         * @return      json
         */
        public function getDeviceComponents($deviceURI, $start=0, $limit=50)
        {
                if(!is_int($start) || !is_int($limit))
                        return false;

                $json_keys = array();
                $json_data = array();
                $json_main = array();

                $json_data['start'] = $start;
                $json_data['limit'] = $limit;
                $json_data['uid'] = $deviceURI;
                $json_data['meta_type'] = "IpInterface";
                $json_data['keys'] = $json_keys;

                $json_main['action'] = "DeviceRouter";
                $json_main['method'] = "getComponents";
                $json_main['data'] = array($json_data);
                $json_main['tid'] = 1;
                $json_main['type'] = "rpc";

                return $this->zQuery($json_main, $deviceURI.'/device_router');
        }


        /**
         * Retrieves Zenoss device details
         *
         * @access      public
         * @param       string
         * @return      json
         */
        public function getDeviceInfo($deviceURI)
        {
                $json_keys = array();
                $json_data = array();
                $json_main = array();

                $json_keys = array("uptime", "firstSeen", "lastChanged", "lastCollected", "locking", "memory", "name", "productionState", "priority",
                                "tagNumber", "serialNumber", "rackSlot", "collector","hwManufacturer","hwModel","osManufacturer","osModel","systems",
                                "groups","location","links","comments","snmpSysName","snmpLocation","snmpContact","snmpDescr","snmpCommunity","snmpVersion");

                $json_data['keys'] = array($json_keys);
                $json_data['uid'] = $deviceURI;

                $json_main['action'] = "DeviceRouter";
                $json_main['method'] = "getInfo";
                $json_main['type'] = "rpc";
                $json_main['data'] = array($json_data);
                $json_main['tid'] = 1;

                return $this->zQuery($json_main, $deviceURI.'/getSubDevices');
        }

        /**
         * Retrieves listing of Zenoss Devices
         *
         * @access      public
         * @param       *int, *int, *str, *str
         * @return      json
         */
        public function getDevices($start=0, $limit=100, $sort="name", $dir="ASC")
        {
                // to do: fix sort validation
                if(!is_int($start) || !is_int($limit))
                        return false;
                if($dir!="ASC" && $dir!="DESC")
                        return false;
                if($sort!="name")
                        return false;

                $json_params = array();
                $json_data = array();
                $json_main = array();

                $json_data['dir'] = $dir;
                $json_data['limit'] = $limit;
                $json_data['sort'] = $sort;
                $json_data['start'] = $start;
                $json_data['params'] = $json_params;

                $json_main['action'] = "DeviceRouter";
                $json_main['method'] = "getDevices";
                $json_main['type'] = "rpc";
                $json_main['data'] = $json_data;
                $json_main['tid'] = 1;

                return $this->zQuery($json_main, '/zport/dmd/Devices/getSubDevices');
        }

        /**
         * Retrieves URL's for Zenoss Device Interface RRD graphs
         *
         * @access      public
         * @param       string, string, *int
         * @return      json
         */
        public function getDeviceInterfaceRRD($deviceURI, $interface, $drange = 129600)
        {
                if(!is_int($drange))
                        return false;

                $json_data = array();
                $json_main = array();

                $json_data['uid'] = $interface;
                $json_data['drange'] = $drange;

                $json_main['action'] = "DeviceRouter";
                $json_main['method'] = "getGraphDefs";
                $json_main['type'] = "rpc";
                $json_main['tid'] = 1;
                $json_main['data'] = array($json_data);

                return $this->zQuery($json_main, $deviceURI.'/device_router');
        }

        /**
         * Retrieves details on specified Zenoss Device Interface
         *
         * @access      public
         * @param       string, string
         * @return      json
         */
        public function getDeviceInterfaceDetails($deviceURI, $interface)
        {
                $json_data = array();
                $json_main = array();

                $json_data['uid'] = $interface;

                $json_main['action'] = "DeviceRouter";
                $json_main['method'] = "getForm";
                $json_main['tid'] = 1;
                $json_main['type'] = "rpc";
                $json_main['data'] = array($json_data);

                return $this->zQuery($json_main, $deviceURI.'/device_router');
        }

        /**
         * Downloads specified image
         *
         * @access      public
         * @param       string, string
         * @return      boolean
         */
        function fetchZenossGraphImage($url)
        {
                $filename = "zenoss_".mt_rand(1000,100000000).'.png';
                $fp = fopen($this->tmp.$filename, "wb");

                // fetch authorization cookie
                $ch = curl_init("{$this->protocol}://{$this->address}:{$this->port}/zport/acl_users/cookieAuthHelper/login");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                curl_exec($ch);

                // fetch image
                curl_setopt($ch, CURLOPT_URL, "{$this->protocol}://{$this->address}:{$this->port}{$url}");
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                curl_exec($ch);

                curl_close($ch);
                fclose($fp);

                return $filename;
        }
}
