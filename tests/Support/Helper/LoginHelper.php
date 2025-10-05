<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use Codeception\Module;

class LoginHelper extends Module
{
    public function login(string $username, string $password): void
    {
        /** @var \Tests\Support\AcceptanceTester $page */
        $page = $this->getModule('WebDriver');
        $page->amOnPage('/login');
        $page->fillField('#usuario', $username);
        $page->fillField('#senha', $password);
        $page->click('button[type="submit"]');
    }

    public function logout(): void
    {
        /** @var \Tests\Support\AcceptanceTester $page */
        $page = $this->getModule('WebDriver');
        $page->click('fulano@example.com');
        $page->click('Sair');
    }
}
