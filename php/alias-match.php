<?php

class AliasMatch {
    
    function CheckAliases(){
        
        if(isset($_POST['aliassubmit'])) {
        
            include("lib/config.php");
            $res_count = 0;
            $response = "";
            
            $alias_table = "SELECT * FROM ".$prefix."_site_content WHERE `alias` LIKE '%/%'";

            if ($result = $mysqli->query($alias_table)) {
                while ($row = $result->fetch_array()) {
                    
                    $frags = explode("/",$row['alias']);
                    
                    $alias_parent = "SELECT * FROM ".$prefix."_site_content WHERE `alias`='".$frags[0]."'";
                    $parent_query = $mysqli->query($alias_parent);
                    $parent_count = mysqli_num_rows($parent_query);
                    
                    if($parent_count < 1){
                        $insert = "INSERT INTO `".$prefix."_site_content` (`pagetitle`,`content`,`template`,`alias`,`published`,`isfolder`) VALUES ('".$frags[0]."',' ','1','".$frags[0]."','1','1')";
                        $mysqli->query($insert);
                        
                        $response = "".$response."<br> ".$frags[0]." made";
                    }
                    
                    //search again
                    $alias_parent = "SELECT * FROM ".$prefix."_site_content WHERE `alias`='".$frags[0]."'";
                    $parent_query = $mysqli->query($alias_parent);
                    $parent_count = mysqli_num_rows($parent_query);
                    $parent_array = $parent_query->fetch_array();
                    
                    if($parent_count){
                        
                        $response = "".$response."<br> ".$row['id']." updated";
                        //$response = "".$response."<br> ".$parent_array['alias']." already made under Root";
                        
                        $parent_update = "UPDATE ".$prefix."_site_content SET `isfolder`='1' WHERE `id`='".$parent_array['id']."'";
                        $mysqli->query($parent_update);
                        
                        $row_update = "UPDATE ".$prefix."_site_content SET `parent`='".$parent_array['id']."' WHERE `id`='".$row['id']."'";
                        $mysqli->query($row_update);
                        
                        $fragstr = "".$frags[0]."/";
                        $new_alias = str_replace($fragstr,"",$row['alias']);
                        
                        $alias_update = "UPDATE ".$prefix."_site_content SET `alias`='".$new_alias."' WHERE `id`='".$row['id']."'";
                        $mysqli->query($alias_update);
                        
                        $res_count++;
                        
                    } 
                    
                    
                    
                }
            }
            
            $response = "".$response."<br><br> ".$res_count." resources updated.";
            
            return $response;
            
            
        }
        
    }
    
    function SecondAliases(){
        
        if(isset($_POST['alias_second_submit'])) {
        
            include("lib/config.php");
            $res_count = 0;
            $response = "";
            
            $alias_table = "SELECT * FROM ".$prefix."_site_content WHERE `alias` LIKE '%/%'";

            if ($result = $mysqli->query($alias_table)) {
                while ($row = $result->fetch_array()) {
                    
                    $frags = explode("/",$row['alias']);
                    
                    $alias_parent = "SELECT * FROM ".$prefix."_site_content WHERE `alias`='".$frags[0]."' AND `parent`='".$row["parent"]."'";
                    $parent_query = $mysqli->query($alias_parent);
                    $parent_count = mysqli_num_rows($parent_query);
                    
                    if($parent_count < 1){
                    //insert a new parent
                    $insert = "INSERT INTO `".$prefix."_site_content` (`pagetitle`,`content`,`template`,`alias`,`published`,`isfolder`,`parent`) VALUES ('".$frags[0]."',' ','1','".$frags[0]."','1','1','".$row["parent"]."')";
                        $mysqli->query($insert);
                    }    
                        
                    //search new parent
                    $alias_parent = "SELECT * FROM ".$prefix."_site_content WHERE `alias`='".$frags[0]."' AND `parent`='".$row["parent"]."'";
                    $parent_query = $mysqli->query($alias_parent);
                    $parent_count = mysqli_num_rows($parent_query);
                    $parent_array = $parent_query->fetch_array();
                    
                    $row_update = "UPDATE ".$prefix."_site_content SET `parent`='".$parent_array['id']."' WHERE `id`='".$row['id']."'";
                    $mysqli->query($row_update);
                    
                    $fragstr = "".$frags[0]."/";
                    $new_alias = str_replace($fragstr,"",$row['alias']);
                    
                    $alias_update = "UPDATE ".$prefix."_site_content SET `alias`='".$new_alias."' WHERE `id`='".$row['id']."'";
                    $mysqli->query($alias_update);
                    
                    $res_count++;
                        
                        
                    
                }
            }
            
            $response = "".$response."<br><br> ".$res_count." resources updated.";
            
            return $response;
            
            
        }
        
    }
    
}
