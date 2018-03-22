<?php
$minierr = "";
$maxerr = "";
$valid = true;
if( $_POST){
	 if ( !preg_match("/^[0-9]+(.[0-9]{2})?$/",$_POST['mini'])||$_POST['mini'] == "" ){
		$minierr = "you must enter a decimal number for minimum price.";
		$valid = false;
	}
	if( !preg_match("/^[0-9]+(.[0-9]{2})?$/",$_POST['max'])||$_POST['max'] == "" ) { 
		$maxerr = "you must enter a decimal number for maximum price.";
		$valid = false;
	}


if($valid){// process form - add to DB and then print out all records
		// get database servername, username, password, and database name
          	//  from local file not on web accessible path (remove newline/blanks)
	$lines = file('/home/int322_153d23/secret/topsecret');
	$dbserver = trim($lines[0]);
	$uid = trim($lines[1]);
	$pw = trim($lines[2]);
	$dbname = trim($lines[3]);
	$mini = $_POST['mini'];
	$max = $_POST['max'];
	$product = $_POST['product'];
	$link = mysqli_connect($dbserver, $uid, $pw, $dbname) or die('could not connect: ' . mysqli_error($link));
	$link2 = file('/home/int322_153d23/apache/cgi-bin/assign1/'. $product .'.txt');
	$deleterecords = 'TRUNCATE TABLE '.$product.''; //empty the table of its current records
 
	mysqli_query($link,$deleterecords);
 
    //Import uploaded file to Database
 
    $handle = fopen("$product.txt", "r");
 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
 
        $import='INSERT into '.$product.'(itemName,price) values("'.$data[0].'","'.$data[1].'")';
 
        mysqli_query($link,$import) or die(mysqli_error());
 
    }
 
    fclose($handle);
 
// Get all records now in DB
	$sql_query = 'SELECT * from '.$product.' where price>= ' . $mini . ' and price<= ' . $max . '';
//Run our sql query
 	$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
 
//iterate through result printing each record
	print "<br>The Products are recommended for you: <br>";
?>


<html>
<body>
<table>
<tr>
<th>Item ID</th><th>Item Name</th><th>Price</th>
<?php
 		while($row = mysqli_fetch_assoc($result))
 		{
?>
		<tr>
        <td><?php print $row['id']; ?></td>
		<td><?php print $row['itemName']; ?></td>
		<td><?php print $row['price']; ?></td>
		</tr>
<?php
 		}
?>
</table>
</body>
</html>
<?php
		// Free resultset
		mysqli_free_result($result);
		//Close the MySQL Link
 		mysqli_close($link);
	}
}
// if not valid or not post then display web form again - otherwise, don't!
if ( !$valid ||  !$_POST ) {
?>
<html>
<body>
<form method="POST" action="">
<select name = "product">
 <option>--Please Choose--</option>
 <option value="cellphone" name="cellphone" <?php if($_POST['product']=="cellphone") echo "selected";?>>cell phones</option>
 <option value="tablet" name="tablet" <?php if($_POST['product']=="tablet") echo "selected";?>>tablets</option>
 <option value="desktop" name="desktop" <?php if($_POST['product']=="desktop") echo "selected";?>>desktops</option>
 <option value="laptop" name="laptop" <?php if($_POST['product']=="laptop") echo "selected";?>>laptops</option>

</select><br/>


minimum price:
 <input type = "text" name="mini" value="<?php if ( isset($_POST['mini']) ) print $_POST['mini']; ?>"><strong><?php print $minierr; ?></strong>
<br />
maximum price:
 <input type="text" name="max" value="<?php if ( isset($_POST['max']) ) print $_POST['max']; ?>"><strong><?php print $maxerr; ?></strong>
<br />
<input type="submit" value = "submit">

</form>
<?php
}
?>
</body>
</html>
