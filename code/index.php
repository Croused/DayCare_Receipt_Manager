<?php
	require_once("./db.php");

	//echo "Hello";
	$mydb = new db();
	$itemTypeList = $mydb->getItemList(); // Gets Item List 

	$dataArray = array('date'=>'1981-01-02', 'store'=>"Test Store 2", 'name'=>'Item Name2',
						'cost'=>"9999.98", 'typeID'=>"1", "comments"=>"asddsfsdfsd");

	// var_dump($mydb->addReceiptItem($dataArray));
	
	//$mydb->getItems() Get last 25 Items from DB
	

	if(isset($_POST["submit"])){
		if($mydb->addReceiptItem($_POST)){
		
			$successMessage = "Item Added";
			
		} else {
			$successMessage = "Something Went Wrong";
			
		}	
	}

	$storeTotals = $mydb->getStoreTotals();
	$itemList = $mydb->getItems();





	echo "<html><head><title>Daycare Receipt Manager</title>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"site.css\">";
	echo "</head><body>";
	echo "<h1>DayCare Receipt Manager</h1>";
	
	if(isset($successMessage)){
		echo "<h2>" . $successMessage . "</h2>";
	}

	echo "<div id=\"formDiv\">";
	echo "<form action=\"?\" method=\"POST\">";
	echo "<div id=\"dateLbl\"><label for\"receiptDate\">Date</label></div>";
	echo "<div id=\"dateInput\"><input type=\"date\" required name=\"date\" id=\"receiptDate\" /></div>";
	
	echo "<div id=\"storeNameLbl\"><label for=\"storeName\">Store</label></div>";
	echo "<div id=\"storeNameInput\"><input type=\"text\" name=\"store\" id=\"storeName\" required /></div>";

	
	echo "<div id=\"itemNameLbl\"><label for=\"itemName\">Item</label></div>";
	echo "<div id=\"itemNameInput\"><input type=\"text\" name=\"name\" id=\"itemName\" required /></div>";
	
	echo "<div id=\"itemCostLbl\"><label for=\"itemCost\">Cost</label></div>";
	echo "<div id=\"itemCostInput\"><input type=\"number\" name=\"cost\" id=\"itemCost\" step=\"0.01\" required/></div>";

	echo "<div id=\"itemTypeLbl\"><label for=\"itemType\">Item Type</label></div>";
	echo "<div id=\"itemTypeInput\"><select name=\"typeID\" id=\"itemType\">";
	if($itemTypeList){
		foreach ($itemTypeList as $itemType){
			//var_dump($itemType);
			echo "<option value=\"" . $itemType["id"] . "\">" . $itemType["itemName"] . "</option>";
		}

	} 
	echo "</select></div>";
	
	echo "<div id=\"commentsLbl\"><label for=\"comments\">Comments</label></div>";
	echo "<div id=\"commentsInput\"><textarea name=\"comments\" id=\"comments\" maxlength=\"256\"></textarea></div>";

	echo "<div id=\"buttonDiv\">";
	echo "<button type=\"submit\" name=\"submit\">Add</button>";
	echo "</div>";
	
	
	echo "</form></div>";
	
	echo "<div id=\"storeTotalDiv\"><table id=\"storeTotalsTable\">";
	echo "<tr><th id=\"storeTotalDateCol\">Date</th><th id=\"storeTotalStoreCol\">Store</th><th id=\"storeTotalCostCol\">Total</th></tr>";
	
	if($storeTotals){
		foreach($storeTotals as $row){
			echo "<tr>";
			echo "<td>" . $row["date"] . "</td>";
			echo "<td>" . $row["store"] . "</td>";
			echo "<td>\$" . $row["cost"] . "</td>";
			echo "</tr>";
		}
	}

	echo "</table></div>";

	echo "<div id=\"itemsDiv\"><table id=\"itemTable\">";
	echo "<tr><th id=\"itemDateCol\">Date</th><th id=\"itemStoreCol\">Store</th>";
	echo "<th id=\"itemNameCol\">Item</th><th id=\"itemCostCol\">Cost</th><th id=\"itemTypeCol\">Type</th></tr>";

	if($itemList){
		foreach($itemList as $row){
			echo "<tr>";
			echo "<td>" . $row['date'] . "</td>";
			echo "<td>" . $row['store'] . "</td>";
			echo "<td>" . $row['name'] . "</td>";
			echo "<td>\$" . $row['cost'] . "</td>";
			echo "<td>" . $row['type'] . "</td>";
			echo "</tr>";
		}
	}

	echo "</table></div>";


	
	//var_dump();

	
	echo "</body></html>";

?>