<?php
require_once("class/Reader.class.php");
$reader = new Reader();
$reader->addURL("IPB RSS feed url 1");
$reader->addURL("IPB RSS feed url 2");
$reader->addURL("IPB RSS feed url 3");
$reader->addURL("IPB RSS feed url 4");
$reader->constructArray();
echo "<pre>";
print_r($reader->displayArray());
echo "</pre>";