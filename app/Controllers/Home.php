<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $sqlFile = ROOTPATH . 'base.sql';
        if (!file_exists($sqlFile)) {
            return "Le fichier base.sql n'existe pas.";
        }

        $sql = file_get_contents($sqlFile);
        $queries = explode(';', $sql);
        
        $errors = 0;
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                if (!$db->query($query)) {
                    $errors++;
                }
            }
        }

        if ($errors === 0) {
            return "La base de données a été injectée avec succès !";
        } else {
            return "Des erreurs sont survenues lors de l'injection.";
        }
    }
}