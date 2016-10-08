<?php
$Shortcut = "[InternetShortcut]
URL=http://decent.592xy.cn/
IDList=
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2
";
Header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=DECENT中国爱好者协会.url;");
echo $Shortcut;
?>