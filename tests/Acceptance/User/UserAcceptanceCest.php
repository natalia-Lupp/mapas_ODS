<?php

namespace Tests\Acceptance\User;

use Database\Populate\AccountRulePopulate;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class UserAcceptanceCest extends BaseAcceptanceCest
{
    public function _before(AcceptanceTester $page): void
    {
        parent::_before($page);
        UserPopulate::populate();
        UserRulePopulate::populate();
        AccountRulePopulate::populate();
    }
    public function loginClient(AcceptanceTester $page): void
    {
        $page->amOnPage('/logout');
        $page->login('user3@email.com', 'SenhaSenha3');
        $page->see('Dashboard Client', 'h2');
    }
    public function theAdminShouldAccessTheClientArea(AcceptanceTester $page): void
    {
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->see('Total de Prédios', 'h5');
        $page->amOnPage('/client');
        $page->see('Dashboard Client', 'h2');
    }
    public function loginAdmin(AcceptanceTester $page): void
    {
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->see('Total de Prédios', 'h5');
    }
    public function denayAdminAccess(AcceptanceTester $page): void
    {
        $page->amOnPage('/logout');
        $page->login('user3@email.com', 'SenhaSenha3');
        $page->amOnPage('/admin');
        $page->see('Dashboard Client', 'h2');
        //$page->see('delete successfully');
    }
    //public function editPet(AcceptanceTester $page): void
    //{
    //    $page->amOnPage('/logout');
    //    $page->login('00000000003', 'SenhaSenha3');
    //    $page->see('Home');
    //    $page->amOnPage('/my/pets#pitokinho');
    //    $page->see('Seus pets');
    //    $page->click('#edit-1');
    //    $page->fillField('#name', 'nicolal');
    //    $page->click("Save");
    //    $page->see('update with success');
    //    $page->see('nicolal');
    //}
}
