<?php

namespace Tests\Acceptance\Admin;

use App\Models\BathroomImage;
use Database\Populate\AccountRulePopulate;
use Database\Populate\BathroomPopulate;
use Database\Populate\BuildingPopulate;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class BathroomImageAcceptanceCest extends BaseAcceptanceCest
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
        $page->click('.link-details-' . self::BATHROOM_ID_TO_INTERACT);
    }

    public function successCreateBathroomImage(AcceptanceTester $page): void
    {
        $page->dontSee('img.bathroom_image');
        $page->dontSee('button.link-submit');

        $page->click('Adicionar imagem');

        $page->scrollTo('#send-image', 200);

        $page->see('Enviar Imagem', 'button.link-submit');

        $page->attachFile('input.field-image', 'feminino-especial-bloco-alunos1.jpg');

        $page->see('Enviar Imagem');
        $page->wait(2);

        $page->click('#send-image');
        $page->wait(3);

        $bathroom_image = BathroomImage::findById(1);

        $path = $bathroom_image->imageService()->path();

        $page->seeElement("img[src=\"$path\"].bathroom_image");

        $page->see('Imagem registrada com sucesso!');

        $bathroom_image->imageService()->deleteImage();
    }

    public function unsuccessCreateBathroomImage(AcceptanceTester $page): void
    {
        $page->dontSee('img.bathroom_image');
        $page->dontSee('button.link-submit');

        $page->click('Adicionar imagem');


        $page->see('Enviar Imagem', '#send-image');

        //$page->attachFile('input.field-image', 'feminino-especial-bloco-alunos1.jpg');

        $page->scrollTo('#send-image', 200);

        $page->wait(2);
        $page->click('#send-image');

        $page->see('Imagem não registrada! Não pode ser vazia');
    }

    public function deleteBathroomImage(AcceptanceTester $page): void
    {
        $page->dontSee('img.bathroom_image');
        $page->dontSee('button.link-submit');

        $page->click('Adicionar imagem');

        $page->scrollTo('#send-image', 200);

        $page->see('Enviar Imagem', 'button.link-submit');

        $page->attachFile('input.field-image', 'feminino-especial-bloco-alunos1.jpg');

        $page->see('Enviar Imagem');
        $page->wait(2);

        $page->scrollTo('#send-image', 200);
        $page->click('#send-image');
        $page->wait(3);

        $page->see('Excluir');

        $page->click('.link-delete-image-1');
        $page->wait(2);

        $page->see('Tem certeza que deseja excluir esta imagem?', 'div.modal-body');

        $page->click('#confirmDeleteImageBtn');

        $page->see('Imagem removida com sucesso!');
    }
}
