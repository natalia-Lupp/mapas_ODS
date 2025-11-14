<?php

namespace Tests\Acceptance\Admin;

use App\Models\Bathroom;
use Database\Populate\AccountRulePopulate;
use Database\Populate\BathroomPopulate;
use Database\Populate\BuildingPopulate;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class BathroomAcceptanceCest extends BaseAcceptanceCest
{
    protected const BLOCK_NAME = 'Bloco A';
    protected const BUILDING_ID = 1;
    protected const BATHROOM_ID_TO_INTERACT = 1;
    protected const NEW_BATHROOM_ID = 4;

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
        $page->see(self::BLOCK_NAME);

        $page->click('.link-bathrooms-' . self::BUILDING_ID);
    }

    public function createBathroom(AcceptanceTester $page): void
    {
        $page->dontSee('Editar', 'a.link-edit-' . self::NEW_BATHROOM_ID);

        $page->click('Novo banheiro');

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms/new');

        $page->selectOption('.field-floor', '1º andar');
        $page->click('.link-submit');

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms');

        $page->see('Editar', 'a.link-edit-' . self::NEW_BATHROOM_ID);
    }

    public function deleteBathroom(AcceptanceTester $page): void
    {
        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms');

        $page->see('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);

        $page->click('button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);

        $page->dontSee('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);
    }

    public function editBathroom(AcceptanceTester $page): void
    {
        $otherBlockId = 2;
        $otherBlockName = 'Bloco B';

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms');
        $page->see('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);

        $page->click('a.link-edit-' . self::BATHROOM_ID_TO_INTERACT);

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT . '/edit');

        $page->selectOption('.field-building_id', $otherBlockName);

        $page->click('button.link-submit');

        $page->seeCurrentUrlEquals('/admin/buildings/' . $otherBlockId . '/bathrooms');

        $page->see('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);
    }

    public function showBathroom(AcceptanceTester $page): void
    {
        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms');

        $page->click('a.link-details-' . self::BATHROOM_ID_TO_INTERACT);

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT);

        $page->see('Predio 1');
        $page->see('Andar 0');

        $page->click('a.link-comeback');

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms');

        $page->click('a.link-details-' . self::BATHROOM_ID_TO_INTERACT);

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT);

        $page->click('a.link-edit');

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID . '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT . '/edit');
    }
}
