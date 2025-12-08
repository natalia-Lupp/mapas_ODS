<?php

namespace App\Middleware;

use App\Models\User;
use Core\Http\Middleware\Middleware;
use Core\Http\Request;

class BasicAuthMiddleware implements Middleware
{
    protected static string $rule = '';
    protected static int $code = 401;

    public function handle(Request $request): void
    {
        $headers = $request->getHeaders();
        $base64 = '';
        if (!isset($headers['Authorization'])) {
            $this->renderJson([
            'message' => 'Acesso negado!',
            'code' => self::$code
            ]);
        }
        $base64 = $headers['Authorization'];
        $base64 = explode(' ', $base64)[1];
        [$email, $password] = explode(':', base64_decode($base64));
        $user = User::findByEmail($email);
        if (!isset($user) || !$user->authenticate($password)) {
            $this->renderJson([
            'message' => 'Acesso negado!',
            'code' => self::$code
            ]);
        }
    }

    /**
     * @param array<string, mixed> $json
     */
    protected function renderJson(array $json = []): void
    {
        header('Content-Type: application/json; chartset=utf-8');
        http_response_code(self::$code);
        echo json_encode($json);
        exit(0);
    }
}
