<?php
// render ('articles/show')
function render(string $path, array $variables = []){
    extract($variables);
    ob_start();
    require('templates/'. $path . '.html.php');
    $pageContent = ob_get_clean();

    require('templates/layout.html.php');

}
/* fonction pour rediriger suite à la suppression  vers une page 
qui doit contenir une chaine de caractere et l'url*/
function redirect(string $url): void {
    header("Location: $url");
    exit();
}