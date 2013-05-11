<?php

$memcache = new Memcache;
$memcache->connect('192.168.0.130', 11211) or die ("Не могу подключиться");

$version = $memcache->getVersion();
echo "Версия сервера: ".$version."<br/>\n";

$tmp_object = new stdClass;
$tmp_object->str_attr = 'test';
$tmp_object->int_attr = 123;

$memcache->set('key', $tmp_object, MEMCACHE_COMPRESSED, 10) or die ("Ошибка при сохранении данных на сервере");
echo "Данные сохранены в кеше. (время жизни данных 10 секунд)<br/>\n";

$get_result = $memcache->get('key');
echo "Данные из кеша:<br/>\n";

var_dump($get_result);

?>