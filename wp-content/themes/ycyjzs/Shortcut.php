<?php
$Shortcut = "[InternetShortcut]
URL=http://www.ycyjzh.com/
IDList=
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2
";
Header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=�γ��ռ�װ�ι������޹�˾.url;");
echo $Shortcut;
?>