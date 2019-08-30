<?php 

class NodeTemplates {
    
    function ImportTemplates(){
        
        include("lib/config.php");
        
        if(isset($_POST['templates'])) {
            foreach($_POST['templates'] as $t){
                $insert = "INSERT INTO `".$prefix."_site_templates` (`templatename`) VALUES ('".$t."')";
                $result = $mysqli->query($insert);
        }
            
            setcookie("DTranspo_Templates", "Added", time()+3600); 
            return "<p>Templates Added</p>";
        }
        
    }
    
}
