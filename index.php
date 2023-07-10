<?php
namespace App;

require_once __DIR__ .  '/vendor/autoload.php';
$user = new User('stas', 26);
$outin = '';
$outin .= '<li class="with_arrow"><span class="pic pic2"></span>'. $region .'<span class="pic_arrow"></span>';
echo $outin;


