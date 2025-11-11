<?php

namespace Tests\Acceptance\Admin;

use Database\Populate\AccountRulePopulate;
use Database\Populate\BathroomPopulate;
use Database\Populate\BuildingPopulate;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class BuildingAcceptanceCest extends BaseAcceptanceCest
{
    public function _before(AcceptanceTester $page): void
    {
        parent::_before($page);
        UserPopulate::populate();
        UserRulePopulate::populate();
        AccountRulePopulate::populate();
        BuildingPopulate::populate();
        BathroomPopulate::populate();
    }
    public function createBuilding(AcceptanceTester $page): void
    {
        $blockName = 'Bloco Z';
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->click('Ver todos');
        $page->dontSee($blockName);
        $page->click('Cadastrar Prédio');
        $page->fillField('input#id_name.form-control', $blockName);
        $page->selectOption('select#id_n_floors.form-select', '8 andares');
        $page->click('Salvar Prédio');
        $page->see($blockName);
    }
    public function deleteBuilding(AcceptanceTester $page): void
    {
        $blockName = 'Bloco F';
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->click('Ver todos');
        $page->see($blockName);
        $page->click('form[action="/admin/buildings/6"] button');
        $page->dontSee($blockName);
    }
    public function failToDeleteBuilding(AcceptanceTester $page): void
    {
        $blockName = 'Bloco A';
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->click('Ver todos');
        $page->see($blockName);
        $page->click('form[action="/admin/buildings/1"] button');
        $page->see($blockName);
        $page->see('Este prédio não pode ser deletado porque possui banheiros relacionados a ele.');
    }
    public function showBuilding(AcceptanceTester $page): void
    {
        $blockName = 'Bloco A';
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->click('Ver todos');
        $page->see($blockName);
        $page->click('a[href="/admin/buildings/1"]');
        $page->see($blockName);
        $page->see('Voltar');
    }
    public function editBuilding(AcceptanceTester $page): void
    {
        $blockName = 'Bloco A';
        $newBlockName = 'Bloco Z';
        $page->amOnPage('/logout');
        $page->login('user1@email.com', 'SenhaSenha1');
        $page->click('Ver todos');
        $page->see($blockName);
        $page->click('a[href="/admin/buildings/1/edit"]');
        $page->fillField('input#building_name.form-control', $newBlockName);
        $page->selectOption('select[name="buildings[n_floors]"]#building_floors.form-select', '8 andares');
        $page->click('Salvar Alterações');
        $page->see($newBlockName);
        $page->dontSee($blockName);
    }
}
