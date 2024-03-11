<?php

require_once('libraries/models/Model.php');
class User extends Model{
    // On inque quelle table qui doit être appelée quand on veut traiter des utilisateurs
    protected $table = "users";
}