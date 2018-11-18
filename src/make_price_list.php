<?php
// make_price_list.php
// read price_list and make trading price set
// input  : price file
// output : price_lsit file

printf("start make_price_list\n");

printf("read input file\n");
$readFileName = "/home/mo-bot/work/goldfish_price_list.tsv";
$readFile = fopen($readFileName,"r");

$line_cnt=0;
$label=array();
$card=array();

// file data read
while( $tsv = fgets($readFile) ){

    $tsv = rtrim($tsv);		// trim lf
    $line = explode("\t",$tsv);

    # 1st line is label
    $col_num = 0;
    if($line_cnt==0){
	foreach( $line as $key => $value) {
            $label[$col_num] = (string)$value;
	    $col_num++;
	}
	$line_cnt++;
	continue;
    }

    # data read
    foreach( $line as $key => $value){
	//var_dump($line);
	$card[$line_cnt][$label[$col_num]] = $value;
	//echo "$value\n";
	$col_num++;
    }
    $line_cnt++;
}
fclose($readFile);

#var_dump($card);
#var_dump($card[1]);
#printf($card[1]["Card"]);


// make price list
// The syntax is: SETNAME;CARDNAME;SELLING PRICE;FOIL SELLING PRICE;BUYING PRICE;FOIL BUYING PRICE;BUYING QUANTITY REGULAR;BUYING QUANTITY FOIL
// or this one: SETNAME;RARITY;SELLING PRICE;FOIL SELLING PRICE;BUYING PRICE;FOIL BUYING PRICE;BUYING QUANTITY REGULAR;BUYING QUANTITY FOIL

$writeFileName = "/home/mo-bot/work/my_price_list.txt";
#$writeFileName = "./work/my_price_list.txt";
$writeFile = fopen($writeFileName,"w");
if($writeFile === FALSE) {
  //エラー
  throw new Exception('Error: Failed to open file (' . filename . ')');
}
#printf($writeFileName);

foreach( $card as $key => $value ){
    fwrite($writeFile,$value["Set"].";");		//SETNAME
    fwrite($writeFile,$value["Card"].";");		//CARDNAME
    fwrite($writeFile,$value["Price"].";");		//SELLING PRICE
    fwrite($writeFile,$value["Price"].";");		//FOIL SELLING PRICE
    fwrite($writeFile,calc_buy_price($value["Set"],$value["Price"]).";");	//BUYING PRICE
    fwrite($writeFile,calc_buy_price($value["Set"],$value["Price"]).";");	//FOIL BUYING PRICE
    fwrite($writeFile,"4".";");		//Buying Quantity Regular
    fwrite($writeFile,"0".";");		//Buying Quantity Foil // Foil is not treat now
    fwrite($writeFile,"\n");
}
fclose($writeFile);


/**********************************************************/
/* function : calc_buy_price 
 *
 * sell price = gold fish price
 * buy price  = gold fish price * ( 100.0 * profit_rate )
 *
 * input  : sell_price 
/* return : buy_price    */
/**********************************************************/
function calc_buy_price( $set , $sell_price ){
    $profit_rate;

    // standard profit rate
    switch($sell_price){
       case $sell_price > 20:
	   $profit_rate = 4.0;
	   break;
       case $sell_price > 15:
	   $profit_rate = 4.5;
	   break;
       case $sell_price > 10:
	   $profit_rate = 5.0;
	   break;
       case $sell_price > 5:
	   $profit_rate = 6.0;
	   break;
       case $sell_price > 2:
	   $profit_rate = 8.0;
	   break;
       case $sell_price > 1.5:
	   $profit_rate = 12.0;
	   break;
       case $sell_price > 1.0:
	   $profit_rate = 15.0;
	   break;
       case $sell_price > 0.5:
	   $profit_rate = 20.0;
	   break;
       case $sell_price > 0.25:
	   $profit_rate = 28.0;
	   break;
       case $sell_price > 0.1:
	   $profit_rate = 38.0;
	   break;
       case $sell_price > 0.01:
	   $profit_rate = 67.0;
	   break;
       case $sell_price > 0.005:
	   $profit_rate = 75.0;
	   break;
       case $sell_price > 0.001:
	   $profit_rate = 80.0;
	   break;
       default:
	   $profit_rate = 80.0;
    }
    // TODO : if set is not standard

    $buy_price = $sell_price * ( 100.0  - $profit_rate ) / 100.0;
    return $buy_price;
}
