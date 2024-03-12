<?php

spl_autoload_register(function($className){
    // ce que l'on reçoit dans la className = Controllers\Article
    // dans classeName on veut remplacer les \\ par des /.
    // require = libraries/Controllers/Article.php;
    $className = str_replace("\\", "/", $className);
    // $className = Controllers/Article.php;
    require_once ("libraries/$className.php");
});