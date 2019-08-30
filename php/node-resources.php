<?php

class NodeResources {
    
    function ImportResources(){
        
        if(isset($_POST['ressubmit'])) {
        
            include("lib/config.php");
            $res_count = 0;
            $node_types = [];
            $node_skip = $_POST['skip'];
            
            $type_query = "SELECT * FROM node_type";
            
            if ($node_result = $mysqli->query($type_query)) {
                while ($row = $node_result->fetch_array()) {
                    array_push($node_types, $row);
                }
            }
            
            $tpls = [];
            $tpls_query = "SELECT * FROM `".$prefix."_site_templates`";
            
            if ($node_result = $mysqli->query($tpls_query)) {
                while ($row = $node_result->fetch_array()) {
                    
                    foreach($node_types as $n){
                        $tpl = [];
                        
                        if($n['name'] === $row['templatename']){
                            $tpl = ['id' => $row['id'], 'name' => $n['type']];
                            
                            array_push($tpls, $tpl);
                        }
                    }
                    
                    
                    
                }
            }
            
            //now we have template id's from MODX and type "names" for the `node` correlation
            
            $node_query = "SELECT * FROM node";
            
            if ($node_result = $mysqli->query($node_query)) {
                while ($row = $node_result->fetch_array()) {
                    
                    //do we skip?
                    if(in_array($row['type'],$node_skip)){
                        continue;
                    }
                    
                    //which template?
                    $template = 1;
                    foreach($tpls as $t){
                        if($t['name'] === $row['type']){
                            $template = $t['id'];
                        }
                    }
                    
                    //get alias
                    $alias = "";
                    $url_query = "SELECT alias FROM url_alias WHERE source = 'node/".$row['nid']."' ";
                    $alias_result = $mysqli->query($url_query);
                    $alias = $alias_result->fetch_array();

                    //get content
                    $content = "";
                    $content_query = "SELECT body_value FROM field_data_body WHERE entity_id = '".$row['nid']."' ";
                    $content_result = $mysqli->query($content_query);
                    $content = $content_result->fetch_array();

                    //Insert to DB
                    $insert = "INSERT INTO `".$prefix."_site_content` (`id`,`pagetitle`,`content`,`template`,`alias`,`published`) VALUES ('".$row['nid']."','".$row['title']."','".$content['body_value']."','".$template."','".$alias['alias']."','".$row['status']."')";
                    $result = $mysqli->query($insert);
                    if ( $result ) {
                        $res_count++;
                    }
                    
                    
                }
            }
            
     
            setcookie("DTranspo_Resources", "Added", time()+33600); 
            return "<p>".$res_count." Resources Added</p>";
            
        
        }    
        
    }
    
}
