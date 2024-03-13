<?php
 class Application{
    public static function process(){
        // definit la class 
        $controllerName = "Article";
        // definit la tâche(fonction)
        $task = "index";
        // si n'est pas vide $_Get controller alors je 
        // veux que $controllerName soit = ucfirst de $_Get controller
        if (!empty($_GET['controller'])){
            //GET => article
            // Article
            $controllerName = ucfirst($_GET['controller']);
        }
        // si n'est pas vide $_Get task alors task est égale ce qu'il y a dans $_Get dans la task
        if (!empty($_GET['task'])){
            $task = $_GET['task'];
        }
        // définit le chemin du fichier à appeler
        $controllerName = "\Controllers\\" . $controllerName;
        // instancie notre classe Article = $controller = new \Controllers\Article();
        $controller = new $controllerName();
        // appel la tâche (funtion) à exécuter depuis notre fichier Controllers\Article()
        $controller->$task();

    }
 }