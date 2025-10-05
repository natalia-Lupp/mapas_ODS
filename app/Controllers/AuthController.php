<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthController extends Controller
{
    public function index(): void
    {
        if (Auth::check()) {
            $this->redirectTo('/');
        }

        $title = 'Login - Mapas ODS';
        $this->render('authentications/login', compact('title'));
    }

    public function processLogin(Request $request): void
    {
        // Captura dados do formulário (campos: 'usuario' e 'senha')
        $email = $request->getParam('usuario', '');
        $password = $request->getParam('senha', '');

        if (empty($email) || empty($password)) {
            FlashMessage::danger('Email e senha são obrigatórios.');
            $this->redirectTo('/login');
            return;
        }

        // Busca usuário por email
        $user = User::findByEmail($email);

        if (!$user) {
            FlashMessage::danger('Credenciais inválidas. Tem certeza de que este é o email correto?');
            $this->redirectTo('/login');
            return;
        }

        // Verifica senha
        if (!$user->authenticate($password)) {
            FlashMessage::danger('Credenciais inválidas. Tem certeza de que digitou a senha corretamente?');
            $this->redirectTo('/login');
            return;
        }

        // Login bem-sucedido
        Auth::login($user);

        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        FlashMessage::success('Login realizado com sucesso!');
        $this->redirectTo('/');
    }

    public function logout(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo('/login');
    }
}
