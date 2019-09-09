<?php
//this page ran manually
//import tables `commerce_product` and `field_data_commerce_price` into your database
//TV in MODX called "SKU" with and ID of 3 - line 19
//TV in MODX called "MSRP" with and ID of 4 - line 29

include("lib/config.php");

$node_query = "SELECT * FROM commerce_product";
            
    if ($node_result = $mysqli->query($node_query)) {
        while ($row = $node_result->fetch_array()) {
            
            //look and insert SKU
            $product_query = "SELECT id FROM ".$prefix."_site_content WHERE pagetitle = '".$row['title']."'";
            $product_result = $mysqli->query($product_query);
            $product = $product_result->fetch_array();
                    
            $product_insert = "INSERT INTO `".$prefix."_site_tmplvar_contentvalues` (`tmplvarid`,`contentid`,`value`) VALUES ('3','".$product['id']."','".$row['sku']."')";
    
            $mysqli->query($product_insert);
            
            //look and insert msrp for SKU just inserted
            $msrp_query = "SELECT * FROM field_data_commerce_price WHERE entity_id = '".$row['product_id']."'";
            $msrp_result = $mysqli->query($msrp_query);
            $msrp = $msrp_result->fetch_array();
            
            if(strlen($msrp['commerce_price_amount']) == 5){
                $msrp_formatted = substr($msrp['commerce_price_amount'],0,3).".".substr($msrp['commerce_price_amount'],-2);
            } else {
                $msrp_formatted = substr($msrp['commerce_price_amount'],0,4).".".substr($msrp['commerce_price_amount'],-2);
            }    
            
            $msrp_insert = "INSERT INTO `".$prefix."_site_tmplvar_contentvalues` (`tmplvarid`,`contentid`,`value`) VALUES ('4','".$product['id']."','".$msrp_formatted."')";
    
            $mysqli->query($msrp_insert);
            
            
            echo $product['id']." - ".$row['title']." - ".$row['sku']." - ".$msrp_formatted."<br>";
            
        }
    }
