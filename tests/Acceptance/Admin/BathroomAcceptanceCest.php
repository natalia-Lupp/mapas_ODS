<?php

namespace Tests\Acceptance\Admin;

use Core\Database\Database;
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
        Database::populate();
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
        $page->waitForElementClickable('select.form-select.field-item-1');
        $page->selectOption('select.form-select.field-item-1', '20');
        $page->click('.link-submit');

        $page->seeCurrentUrlEquals('/admin/buildings/bathrooms?building_id=' . self::BUILDING_ID);

        $page->see('20');
        $page->see('Editar', 'a.link-edit-' . self::NEW_BATHROOM_ID);
    }

    public function deleteBathroom(AcceptanceTester $page): void
    {
        $page->seeCurrentUrlEquals('/admin/buildings/bathrooms?building_id=' . self::BUILDING_ID);

        // Verifica se o botão existe
        $page->see('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);

        // Abre o modal
        $page->click('button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);

        // Aguarda o modal aparecer
        $page->waitForElementVisible('#deleteConfirmModal', 3);

        // Clica no botão "Excluir" do modal
        $page->click('#confirmDeleteBtn');

        // Agora deve desaparecer o botão da linha deletada
        $page->dontSee('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);
    }


    public function editBathroom(AcceptanceTester $page): void
    {
        $otherfloor = '2º andar';

        $page->seeCurrentUrlEquals('/admin/buildings/bathrooms?building_id='
            . self::BUILDING_ID);
        $page->see('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);

        $page->click('a.link-edit-' . self::BATHROOM_ID_TO_INTERACT);

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID .
            '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT . '/edit');

        $page->selectOption('select.form-select.field-floor', $otherfloor);
        $page->waitForElementClickable('select.form-select.field-item-1');
        $page->selectOption('select.form-select.field-item-1', '20');

        $page->click('button.link-submit');

        $page->seeCurrentUrlEquals('/admin/buildings/bathrooms?building_id=' . self::BUILDING_ID);
        $page->see('26');
        $page->see('Excluir', 'button.link-delete-' . self::BATHROOM_ID_TO_INTERACT);
    }

    public function showBathroom(AcceptanceTester $page): void
    {
        $page->seeCurrentUrlEquals('/admin/buildings/bathrooms?building_id=' . self::BUILDING_ID);

        $page->click('a.link-details-' . self::BATHROOM_ID_TO_INTERACT);

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID .
            '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT);

        $page->see(self::BLOCK_NAME);
        $page->see('Este banheiro possui 9 itens no total, sendo:');
        $page->see('Andar 1');
        $page->see('torneira de lavado : 3');
        $page->see('Vaso sanitário : 3');
        $page->see('Mictório : 3');
        $page->see('350L');
        $page->see('250L');

        $page->click('a.link-comeback');

        $page->seeCurrentUrlEquals('/admin/buildings/bathrooms?building_id=' . self::BUILDING_ID);

        $page->click('a.link-details-' . self::BATHROOM_ID_TO_INTERACT);

        $page->seeCurrentUrlEquals('/admin/buildings/' . self::BUILDING_ID .
            '/bathrooms/' . self::BATHROOM_ID_TO_INTERACT);

        $page->dontSee('Enviar Imagem', '.link-submit');

        $page->click('Adicionar imagem');

        $page->see('Enviar Imagem', '.link-submit');
    }

    public function showBathroomItemType(AcceptanceTester $page): void
    {
        $page->click('.link-items-' . self::BATHROOM_ID_TO_INTERACT);
        $page->see('torneira de lavado');
        $page->click('torneira de lavado');
        $page->see('tipo de iten de banheiro monitorado', 'h2');
        $page->see('torneira de lavado', 'h4');
        $page->see('Consumo medio: 1.5L');
        $page->see('Total de itens: 3');
        $page->see('Bloco A andar 1 detalhes');
        $page->click('Voltar');
    }
}
