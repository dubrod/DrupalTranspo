<?php
include_once("php/lib/bootstrap.php");
include("node_export.php");

foreach($nodes as $n){
    
    //"Catalog Image"
    echo "Catalog Image: ".$n->field_product_catalog_image['und'][0]['filename'];
    
    $catalog_tmplvarid = 2; //Made this TV manually in MODX
    $catalog_contentid = $n->vid;
    $catalog_value = "default/files".$n->field_product_catalog_image['und'][0]['filename'];
    //adding "default/files" because of drupal site structure. just going to dump the folder onto the new server
    
    $insert = "INSERT INTO `".$prefix."_site_tmplvar_contentvalues` (`tmplvarid`,`contentid`,`value`) VALUES ('".$catalog_tmplvarid."','".$catalog_contentid."','".$catalog_value."')";
    
    $mysqli->query($insert);
    
    
}
