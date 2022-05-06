<?php
if(array_key_exists('S',$_REQUEST)) $S_string = $_REQUEST['S'];
else {echo "Не передано значение подстроки S."; return;}
if(array_key_exists('N', $_REQUEST)) $N_max_num = $_REQUEST['N'];
else {echo "Не передано значение максимума результатов N."; return;}

$mas1 = array();$mas2 = array();$mas3 = array();
$f1 = fopen('streets.csv', 'r');
$f2 = fopen('houses.csv', 'r');
$f3 = fopen('quests.csv', 'r');
$line = fgets($f1);
while ($line != null)
{
    $mas1[] = trim($line);
    $line = fgets($f1);
}
$line = fgets($f2);
while ($line != null)
{
    $buf = explode(',', $line);
    $key = trim($buf[0]);
    $buf = array_slice($buf, 1);
    $mas2[$key] = $buf;
    $line = fgets($f2);
}
$line = fgets($f3);
while ($line != null)
{
    $buf = explode(',', $line);
    $key1 = trim($buf[0]);
    $key2 = (int)$buf[1];
    $mas3[$key1][$key2] = (int)$buf[2];
    $line = fgets($f3);
}
//print_r($mas3);
$appropriate_streets = array();
$appropriate_houses = array();
for ($i = 0; $i < count($mas1); $i++)
    if (strpos(mb_strtolower($mas1[$i]), mb_strtolower($S_string)) !== false) $appropriate_streets[] = $mas1[$i];
foreach ($appropriate_streets as $v) {
    if (!array_key_exists($v, $appropriate_houses)) $appropriate_houses[$v] = array();
    foreach ($mas2[$v] as $vv) {
        $vv = (int)$vv;
        $bff = $mas3[$v];
        if (array_key_exists($vv, $bff)) $appropriate_houses[$v][$vv] = $bff[$vv];
    }
}
$quests = array();
foreach ($appropriate_houses as $k => $v)
    foreach ($v as $kk => $vv)
        $quests[$vv] = $k." ".$kk;
uksort ($quests, function ($a, $b){ return -$a+$b;});
$n = 0;
$strin = "";
foreach ($quests as $k => $v)
{
    if ($n >= $N_max_num) break;
    $buf = explode(' ',$v); $u = $buf[0]; $d = $buf[1];
    $strin .= "<br>Ул. $u, Д. $d, число заявок - $k<br>";
    $n++;
}
echo  $strin;
fclose($f1);fclose($f2);fclose($f3);
?>
