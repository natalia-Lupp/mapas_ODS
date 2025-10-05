<?php

namespace App\Controllers;

class PreviewController
{
    public function login(): void
    {
        // Variáveis que a view espera
        $username = '';
        $error = '';

        // Inclui a view
        include __DIR__ . '/../views/authentications/login.phtml';
    }
}
