<?php

$memcache = new Memcache;
$memcache->connect('192.168.0.130', 11211) or die ("�� ���� ������������");

$version = $memcache->getVersion();
echo "������ �������: ".$version."<br/>\n";

$tmp_object = new stdClass;
$tmp_object->str_attr = 'test';
$tmp_object->int_attr = 123;

$memcache->set('key', $tmp_object, MEMCACHE_COMPRESSED, 10) or die ("������ ��� ���������� ������ �� �������");
echo "������ ��������� � ����. (����� ����� ������ 10 ������)<br/>\n";

$get_result = $memcache->get('key');
echo "������ �� ����:<br/>\n";

var_dump($get_result);

?>