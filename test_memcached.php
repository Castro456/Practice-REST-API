<?php

$memcached = new Memcached();
 $memcached->addServer("localhost",11211); # You might need to set "localhost" to "127.0.0.1"
 echo "Server's version:  ";
 print_r($memcached->getVersion());
 echo "<br />\n";
 $tmp_object = new stdClass;
 $tmp_object->str_attr = "test";
 $tmp_object->int_attr = 123;
 $memcached->set("key",$tmp_object,10);
 echo "Store data in the cache (data will expire in 10 seconds)<br />\n";
 echo "Data from the cache:<br />\n";
 var_dump($memcached->get("key"));

?>