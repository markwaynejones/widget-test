<?

if(isset($_POST['submit']))
{

	$qtyOrdered = $_POST['num-of-widgets'];
		
	// if over 5000 need to break it down
	if($qtyOrdered > 5000)
	{

		$packsToOrder = array();

		$newQtyOrdered = floor($qtyOrdered / 5000);

		// loop through each 5000
		for($i = 0; $i < floor($newQtyOrdered); $i++)
		{
			$packsToOrder[] = '1 x 5000';
		}

		$qtyLeft = $qtyOrdered - ($newQtyOrdered * 5000);

		// pass through the remaining widgets to our recursive function
		$packsToOrder = recurse($qtyLeft, $packsToOrder);

	}
	else
	{
		$packsToOrder = recurse($qtyOrdered);
	}
}

function recurse($qtyOrdered, &$packsToOrder = array())
{
	$widgetPacks = array(
		250,
		500,
		1000,
		2000,
		5000
	);

	$widgetPacksReversed = array_reverse($widgetPacks);

	foreach($widgetPacksReversed as $key => $widgetPackQty)
	{
		// if exact match
		if($qtyOrdered == $widgetPackQty)
		{
			$packsToOrder[] = '1 x'.$widgetPackQty;
			return $packsToOrder;
		}
 
		$nextKey = $key + 1;
		$nextNextKey = $key + 2;
		$previousKey = $key - 1;

		//if between two pack numbers (500 and 1000. Current is 1000)
		if (($qtyOrdered < $widgetPackQty) && ($qtyOrdered > $widgetPacksReversed[$nextKey]))
		{

			$newQty = $qtyOrdered - $widgetPacksReversed[$nextKey];

			if($newQty < $widgetPacksReversed[$nextNextKey])
			{
				$packsToOrder[] = '1 x '.$widgetPacksReversed[$nextKey];
				
				recurse($newQty, $packsToOrder);
			}
			else
			{
				$packsToOrder[] = '1 x '.$widgetPackQty;
			}
		}

	}

	return $packsToOrder;
	
}

?>

<h1>Widget Order Program</h1>

<p>Please enter number of widgets you would like to purchase and 
the program will work out what packs will need ordering</p>

<form method="POST" action="http://localhost/flatten-json-test/widget-test.php">

	<p>Number of widgets to order: &nbsp;<input name="num-of-widgets" type="text" /></p>

	<p><input name="submit" type="submit" value="Submit" /></p>

</form>

<?
// print results to screen
if(isset($_POST['submit']))
{

	echo '<h3>Packs to order</h3>';

	echo '<ul style="color:green;">';

	foreach($packsToOrder as $currentPack)
	{
		echo '<li>'.$currentPack.'</li>';
	}

	echo '</ul>';

}
?>