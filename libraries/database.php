<?php

class Database{

private static $instance =null;
        /**
 * 1. Connexion à la base de données avec PDO
 * Attention, on précise ici deux options :
 * - Le mode d'erreur : le mode exception permet à PDO de nous prévenir violament quand on fait une connerie ;-)
 * - Le mode d'exploitation : FETCH_ASSOC veut dire qu'on exploitera les données sous la forme de tableaux associatifs
 * Return une connexion à la  BDD 
 * @return PDO quelque chose de type
 */
public static function getPdo(): PDO {
    // si la propriété instance est strictement égal à null (pas d'instance de PDO)
    if (self::$instance === null){
        self::$instance = new PDO('mysql:host=localhost;dbname=blogpoo;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$instance;
    }
       
    }
