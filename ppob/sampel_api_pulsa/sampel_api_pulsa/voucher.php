<?php
require_once __DIR__ . '/../_Pulsa/Helper.php';

$helper = new Helper;

if (!empty($_POST)) 
	$ii = $helper->inquiry($_POST);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sampel API Pulsa</title>
</head>
<body>
	<form action="purchase.php" method="post" name="inquiry">
		<input type="hidden" name="phone" value="<?php echo $_POST['phone'] ?>">
		<input type="hidden" name="harga">
		<table width="500px" border="1">
			<tr>
				<td>Pilih</td>
				<td>Produk Id</td>
				<td>Voucher</td>
				<td>Nominal</td>
				<td>Harga</td>
			</tr>
			<?php $i = 0; while ( $i < sizeof($ii)) { ?>
			<tr>
				<td><input type="radio" name="id" value="<?php echo $ii[$i]['product_id'] ?>" onclick="document.inquiry.harga.value='<?php echo $ii[$i]['price'] ?>'" /></td>
				<td><?php echo $ii[$i]['product_id'] ?></td>
				<td><?php echo $ii[$i]['voucher'] ?></td>
				<td><?php echo $ii[$i]['nominal'] ?></td>
				<td><?php echo $ii[$i]['price'] ?></td>
			</tr>
			<?php $i++; } ?>
		</table>
		<button type="submit">OK</button>
	</form>
</body>
</html>