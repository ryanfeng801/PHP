
<?php

$minerr = "";
$maxerr = "";
$valid = true;

if($_POST){
	
      $reg ='/^[0-9]+(.[0-9]{2})?$/';

    if($_POST['product'] == "" || $_POST['product'] == "no")
	{
		$proerr= "you must select one product.";
		$valid = false;
	}

    if ( !preg_match($reg,$_POST['min'])||$_POST['min'] == "" ){
		$minerr = "you must enter a decimal number for minimum price.";
		$valid = false;
	}
	
	if( !preg_match($reg,$_POST['max'])||$_POST['max'] == "" ) { 
		$maxerr = "you must enter a decimal number for maximum price.";
		$valid = false;
	
	
}

}

	
if($_POST && $valid){
	
	$lines = file('/home/int322_161a24/secret/topsecret.txt');
	$dbserver = trim($lines[0]);
	$uid = trim($lines[1]);
	$pw = trim($lines[2]);
	$dbname = trim($lines[3]);
	
	$min = $_POST['min'];
	$max = $_POST['max'];
	$product = $_POST['product'];
	
	$link = mysqli_connect($dbserver, $uid, $pw, $dbname) or die('could not connect: ' . mysqli_error($link));
	
	
	
	$link2 = file('/home/int322_161a24/apache/cgi-bin/assign1/cellphone.txt');
	
	$drop = 'drop TABLE cellphone'; 
 
	mysqli_query($link,$drop); //can modified table easy
	
	$tablename= "cellphone";
	
 
    $create = 'create table cellphone (
               id int zerofill not null auto_increment,
               model varchar(40) not null, 
			   os varchar(10) not null,
			   version varchar(20) not null,
               price decimal(10,2) not null,
               primary key (id)
                    ); ';
					
	mysqli_query($link,$create);
	
	
	

   $handle = fopen("cellphone.txt", "r");
 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
 
        $insert='INSERT into cellphone(model,os,version,price) values("'.$data[0].'","'.$data[1].'","'.$data[2].'","'.$data[3].'")';
 
        mysqli_query($link,$insert) or die(mysqli_error());
 
    }
 
     
    fclose($handle);
	
	function choose($a)
	
	{
	
	switch($a)
	{
		case '6':
		$b="0000000001";
		break;
		case '6s':
		$b="0000000002";
		break;
		case '6p':
		$b="0000000003";
		break;
		case '6sp':
		$b="0000000004";
		break;
		case 's4':
		$b="0000000005";
		break;
		case 'n4':
		$b="0000000006";
		break;
	}
	return $b;
	}
	
      $idnum = choose($product);
	
	
	$sql_query1= 'SELECT * from cellphone where id= '.$idnum.'';

 	$result1 = mysqli_query($link, $sql_query1) or die('query failed'. mysqli_error($link));
 
	$sql_query2= 'SELECT * from cellphone where price>= ' . $min . ' and price<= ' . $max . '';

    $result2 = mysqli_query($link, $sql_query2) or die('query failed'. mysqli_error($link));


?>

<html>
<body>

<h2> Here is the information of the cellphone which you choose:</h2>
<table>
<tr>
<th>Item id</th><th>Item Name and model</th><th>os</th><th>version</th><th>price</th>
<?php
 		while($row = mysqli_fetch_assoc($result1))
 		{
?>
		<tr>
        <td><?php print $row['id']; ?></td>
		<td><?php print $row['model']; ?></td>
		<td><?php print $row['os']; ?></td>
		<td><?php print $row['version']; ?></td>
		<td><?php print $row['price']; ?></td>
		</tr>
<?php
 		}
?>
</table>

<?php
		
		mysqli_free_result($result1);
		
?> 		
		
<h2> There are the informations about the cellphones which you choose the range of price:</h2>
<table>
<tr>
<th>Item id</th><th>Item Name and model</th><th>os</th><th>version</th><th>price</th>
<?php
 		while($row = mysqli_fetch_assoc($result2))
 		{
?>
		<tr>
        <td><?php print $row['id']; ?></td>
		<td><?php print $row['model']; ?></td>
		<td><?php print $row['os']; ?></td>
		<td><?php print $row['version']; ?></td>
		<td><?php print $row['price']; ?></td>
		</tr>
<?php
 		}
?>
</table>

<?php
		
		mysqli_free_result($result2);
		
 		mysqli_close($link);		
	
}	

else  {
?>	


<form method="POST" action="">

<select name = "product">
 <option value="no" name="no">--Please Choose--</option>
 <option value="6" name="6"  <?php if($_POST['product']=="6") echo "selected";?>>iphone 6 64GB</option>
 <option value="6s" name="6s" <?php if($_POST['product']=="6s") echo "selected";?>>iphone 6s 64GB</option>
 <option value="6p" name="6p"  <?php if($_POST['product']=="6p") echo "selected";?>>iphone 6 plus 64GB</option>
 <option value="6sp" name="6sp" <?php if($_POST['product']=="6sp") echo "selected";?>>iphone 6s plus 64GB</option>
 <option value="s4" name="s4"  <?php if($_POST['product']=="s4") echo "selected";?>>samsung galaxy s4 lte i9505</option>
 <option value="n4" name="n4" <?php if($_POST['product']=="n4") echo "selected";?>>samsung galaxy note4 sm-n910</option>
 
</select>
<?php echo $proerr;?>
<br/>


<p>minimum price:<input type="text" name="min" value="<?php if ( isset($_POST['min']) ) print $_POST['min']; ?>"><?php print $minerr; ?></p>

<p>maximum price:<input type="text" name="max" value="<?php if ( isset($_POST['max']) ) print $_POST['max']; ?>"><?php print $maxerr; ?></p>

<input type="submit" value = "submit">

</form>
<?php
}
?>

</body>
</html>

