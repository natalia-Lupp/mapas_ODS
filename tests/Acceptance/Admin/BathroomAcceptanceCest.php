<?php

namespace Tests\Acceptance\Admin;

use Database\Populate\AccountRulePopulate;
use Database\Populate\BathroomPopulate;
use Database\Populate\BuildingPopulate;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class BathroomAcceptanceCest extends BaseAcceptanceCest
{
    protected const blockName = 'Bloco A';

    public function _before(AcceptanceTester $page): void
    {
        parent::_before($page);
        UserPopulate::populate();
        UserRulePopulate::populate();
        AccountRulePopulate::populate();
        BuildingPopulate::populate();
        BathroomPopulate::populate();
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->click('Ver todos');
        $page->see(self::blockName);
        $page->click('.link-bathrooms-1');
    }
    public function createBathroom(AcceptanceTester $page): void
    {
        $page->dontSee('Editar', 'a.link-edit-4');
        $page->click('Novo banheiro');
        //$page->fillField('.field-name', self::blockName);
        $page->selectOption('.field-floor', '1º andar');
        $page->click('.link-submit');
        $page->see('Editar', 'a.link-edit-4');
    }
    public function deleteBathroom(AcceptanceTester $page): void
    {
        $page->see('Excluir', 'button.link-delete-1');
        $page->click('button.link-delete-1');
        $page->dontSee('Excluir', 'button.link-delete-1');
    }
    //public function failToDeleteBuilding(AcceptanceTester $page): void
    //{
    //    $blockName = 'Bloco A';
    //    $page->amOnPage('/logout');
    //    $page->login('user1@email.com', 'SenhaSenha1');
    //    $page->click('Ver todos');
    //    $page->see($blockName);
    //    $page->click('form[action="/admin/buildings/1"] button');
    //    $page->see($blockName);
    //    $page->see('Este prédio não pode ser deletado porque possui banheiros relacionados a ele.');
    //}
    public function editBathroom(AcceptanceTester $page): void
    {
        $otherBlock = 'Bloco B';
        $page->see('Excluir', 'button.link-delete-1');
        $page->click('a.link-edit-1');
        $page->selectOption('.field-building_id', $otherBlock);
        $page->click('button.link-submit');
        $page->dontSee('Editar', 'a.link-edit-1');
        $page->amOnPage('/admin/buildings/2/bathrooms');
        $page->see('Excluir', 'button.link-delete-1');
    }

    public function showBathroom(AcceptanceTester $page): void
    {
        $page->seeCurrentUrlEquals('/admin/bathrooms?building_id=1');
        $page->click('a.link-details-1');
        $page->seeCurrentUrlEquals('/admin/bathrooms/1');
        $page->see('Predio 1');
        $page->see('Andar 0');
        $page->click('a.link-comeback');
        $page->seeCurrentUrlEquals('/admin/buildings/1/bathrooms');
        $page->click('a.link-details-1');
        $page->seeCurrentUrlEquals('/admin/bathrooms/1');
        $page->click('a.link-edit');
        $page->seeCurrentUrlEquals('/admin/bathrooms/1/edit');
    }
}
