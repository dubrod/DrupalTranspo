<?php
include_once("php/lib/bootstrap.php");

$node_table = "SELECT * FROM node";
$nodes = $mysqli->query($node_table);
$node_count = mysqli_num_rows($nodes);

$alias_table = "SELECT alias FROM ".$prefix."_site_content WHERE `alias` LIKE '%/%'";
$aliass = $mysqli->query($alias_table);
$alias_count = mysqli_num_rows($aliass);

$node_tpls = ""; $node_cates = ""; $node_skip = "";

$node_query = "SELECT * FROM node_type";
if ($node_result = $mysqli->query($node_query)) {
    while ($row = $node_result->fetch_array()) {
        
        $node_tpls .= '<label for="'.$row['name'].'"><input type="checkbox" value="'.$row['name'].'" id="'.$row['name'].'" name="templates[]"> '.$row['name'].' </label>';
        $node_cates .= '<label for="'.$row['name'].'"><input type="checkbox" value="'.$row['name'].'" id="'.$row['name'].'" name="categories[]"> '.$row['name'].' </label>';
        $node_skip .= '<label for="'.$row['name'].'"><input type="checkbox" value="'.$row['type'].'" id="'.$row['name'].'" name="skip[]"> '.$row['name'].' </label>';
        
        
    }
}

include_once('php/node-types.php');
$NodeTemplates = new NodeTemplates;
$tplsuccess = $NodeTemplates->ImportTemplates();

include_once('php/node-categories.php');
$NodeCategories = new NodeCategories;
$catesuccess = $NodeCategories->ImportCategories();

include_once('php/node-resources.php');
$NodeResources = new NodeResources;
$ressuccess = $NodeResources->ImportResources();

include_once('php/alias-match.php');
$AliasMatch = new AliasMatch;
$alias_success = $AliasMatch->CheckAliases();
$alias_second_success = $AliasMatch->SecondAliases();


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Drupal Transpo</title>
    <link href="http://fonts.googleapis.com/css?family=Raleway:700,300" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/prettify.css">
</head>

<body>
    <nav>
        <div class="container">
            <h1>Drupal Transpo</h1>
            <div id="menu">
                <ul class="toplinks">
                    <li><a href="https://github.com/dubrod/WPtranspo" target="_blank">Github Repo</a></li>
                </ul>
            </div>
            <a id="menu-toggle" href="#" class=" ">&#9776;</a> </div>
    </nav>
    <header>
        <div class="container">
            <h2 class="docs-header">Join the Revolution</h2>
        </div>
    </header>
    
    <div class="container">
        
        <article>
        
            <h2> Getting Started</h2>
            <p>In order for this to work the following must be <strong>TRUE</strong>:</p>
            <ul style="font-size: 14px;">
                <li>You have installed MODX Revolution, and this package is on the server with it.</li>
                <li>You have <em>Truncated</em> the table `modx_site_content`</li>
                <li>You have updated "php/lib/config.php" with the Database Credentials</li>
                <li>You are logged in to the manager, or have an open session.</li>
                <li>Created and Inserted the table `node` from Drupal</li>
                <li>Created and Inserted the table `node_type` from Drupal</li>
                <li>Created and Inserted the table `url_alias` from Drupal</li>
                <li>Created and Inserted the table `field_data_body` from Drupal</li>
            </ul>
        
        </article>
        
        <article>
        
        <h3>Templates</h3>
        
        <p>"Page" nodes will be assigned to the Default MODX template already installed.</p>
        
        <?php if(isset($_COOKIE["DTranspo_Templates"])): ?>
        
        <p>Templates already created.</p>
        
        <?php else: ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
            <p>Check which Node Types need to have templates created?</p>
            <p style="padding:0 1rem;">
                <?php echo $node_tpls; ?>
            </p>

            <button>CREATE</button>
        </form>
        
        <?php endif; ?>
                
        <div class="response-section"><?php echo $tplsuccess; ?></div>

        </article>
        <article>
        
        <h3>Categories</h3>
        
        <?php if(isset($_COOKIE["DTranspo_Categories"])): ?>
        
        <p>Categories already created.</p>
        
        <?php else: ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
            <p>Check which Node Types need to have categories created?</p>
            <p style="padding:0 1rem;">
                <?php echo $node_cates; ?>
            </p>
            <button>CREATE</button>
        </form>
        
        <?php endif; ?>
        
        <div class="response-section"><?php echo $catesuccess; ?></div>
        
        </article>

    </div> <!-- container -->
    
    <hr>
    
    <div class="container">
        
    <article>
        
        <h3>Resources</h3>    
        <p>Turning Nodes into Resources by looking at `node` table, inserting ID, Title and Statues. Comparing `type` to previously created MODX templates for assignment.</p>
        <p>Next looking at `url_alias` table for matching `nid`.</p>
        
        <p>Finally, look up `field_data_body` table and insert body data as 'content'.</p>
        
        
    </article>
    
    <article>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
            <?php if(isset($_COOKIE["DTranspo_Resources"])): ?>
            
            <p><strong>Nodes converted already.</strong></p>
            
            <?php else: ?>
            
            <p><?php echo $node_count; ?> Nodes</p>
            <p>Check which Node Types to <strong>NOT</strong> create resources for?</p>
            <p style="padding:0 1rem;">
                <?php echo $node_skip; ?>
            </p>
            
            <button name="ressubmit">CREATE</button>
            <?php endif; ?>
        </form>
        
        <div class="response-section"><?php echo $ressuccess; ?></div>
        
    </article>
    
    <article>
        
        <h3>Alias Cleanup</h3>
        <p>Next looking at `alias` in `site_content` table for '/'. We will explode that string and create parent resources if necessary.</p>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p> <?php echo $alias_count; ?> aliases with a /. Refresh page if 0.</p>
            
            <button name="aliassubmit">CLEANUP ROOT</button>
        </form>
        
        <div class="response-section"><?php echo $alias_success; ?></div>
        
        <hr>
        
        <p>Need a second level clean up?</p>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button name="alias_second_submit">CLEANUP MORE</button>
        </form>
        
        <div class="response-section"><?php echo $alias_second_success; ?></div>
        
        
    </article>

    </div> <!-- container -->
    
    <section class="vibrant centered">
        <div class="container">
            
        </div>
    </section>

</body>
</html>
