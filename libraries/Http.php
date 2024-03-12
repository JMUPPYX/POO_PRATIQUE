<?php

class Http{
    /* fonction pour rediriger suite à la suppression  vers une page 
    qui doit contenir une chaine de caractere et l'url*/
    public static function redirect(string $url): void {
    header("Location: $url");
    exit();
    }
}