<?php

class NodeCategories {
    function ImportCategories() {
        
        include("lib/config.php");
        
        if(isset($_POST['categories'])) {
            foreach($_POST['categories'] as $t){
                $insert = "INSERT INTO `".$prefix."_categories` (`category`) VALUES ('".$t."')";
                $result = $mysqli->query($insert);
                
            }
            
            setcookie("DTranspo_Categories", "Added", time()+3600); 
            return "<p>Categories Added</p>";
        }

        
    }
}
