<?php 

$fileName='Dead Languages.txt';

$file=fopen($fileName,'r+');

$content=fread($file,filesize($fileName));


print "<br>--------------this is the old file-----------------<br><br>".$content."<br><br>";


$preedit=substr_count($content,"wh");

//echo $preedit;

$roundbracket='/\(.*\)/';
$replacement1='(*wh*)';
$content=preg_replace($roundbracket,$replacement1,$content);

$postedit=substr_count($content,'*wh*');

//echo $postedit;


print "<br>--------------this is the new file-----------------<br><br>".$content."<br><br>";

if (ftell($file)>0)
{
	fseek($file,0);
} 


fwrite ($file,$content);

fseek($file,782);

$partfile=fread($file,filesize($fileName));

$string = '/what/';
$replacement2='which';

$selection=substr_count($partfile,"wha");

//echo $selection;


$partfile=preg_replace($string,$replacement2,$partfile);

$content=substr_replace($content,$partfile,782);



if (ftell($file)>0)
{
	fseek($file,0);
} 


fwrite ($file,$content);
fclose($file);

print "<br>--------------this is the newest file-----------------<br><br>".$content."<br><br>";

$lines=file('/home/int322_161a24/apache/cgi-bin/lab4/lab4id.txt');
$dbserver=trim($lines[0]);
$uid=trim($lines[1]);
$pw=trim($lines[2]);
$dbname=trim($lines[3]);


$link=mysqli_connect($dbserver,$uid,$pw,$dbname) or die('Could not connect: '.mysqli_error($link));

$sql_query='insert into editing set preedit="'.$preedit.'", postedit="'.$postedit.'",selection="'.$selection.'"';

$sql_query = "SELECT * FROM editing";

$result=mysqli_query($link,$sql_query) or die ('query failed'.mysqli_error($link));

mysqli_close($link);

?>

<html>
  <body>
  <table border = "1">
  <tr>
    <th>preedit</th><th>postedit</th><th>selection</th>

  <?php 


    while ($row = mysqli_fetch_assoc($result))
    {
  ?>
    <tr>
    <td><?php print $row['preedit']; ?></td>
    <td><?php print $row['postedit']; ?></td>
    <td><?php print $row['selection']; ?></td>
    
	  

    </tr>
	</tr>
  <?php
    }
  ?>
</body>
</html>