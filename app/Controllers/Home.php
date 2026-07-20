<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
    //    return view('welcome_message');
    
    $db = \Config\Database::connect();
    
    // Lit ton fichier base.sql à la racine
    $sql = file_get_contents(ROOTPATH . 'base.sql');
    
    // Injecte le SQL directement dans SQLite
    if ($db->simpleQuery($sql)) {
        return "La base de données a été injectée avec succès !";
    } else {
        return "Erreur lors de l'injection.";
    }
    }
}
