<?php

namespace Tests\Unit\Models;

use App\Models\Bathroom;
use App\Models\BathroomImage;
use App\Models\Building;
use App\Models\ImageModel;
use Core\Constants\Constants;
use Tests\TestCase;

class BathroomImageTest extends TestCase
{
    protected const string TEST_IMAGE_SRC = '/var/www/tests/Support/Data/feminino-especial-bloco-alunos1.jpg';
    protected string $test_image_name = 'feminino-especial-bloco-alunos2.jpg';
    protected string $test_image = '';

    private int $test_image_size = 0;
    private string $test_image_mime_type = '';

    protected ?Building $building;
    protected ?Bathroom $bathroom;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_image = Constants::rootPath()
             ->join("tests/Support/Data/{$this->test_image_name}");
        $this->test_image_size =  filesize(self::TEST_IMAGE_SRC);
        $this->test_image_mime_type =  mime_content_type(self::TEST_IMAGE_SRC);

        $this->building = new Building([
        'name' => 'H',
        'n_floors' => 3
        ]);
        $this->building->save();
        $this->bathroom = new Bathroom([
        'floor' => 1,
        'building_id' => 1
        ]);
        $this->bathroom->save();

        copy(self::TEST_IMAGE_SRC, $this->test_image);
        /**
         * @var ImageModel $image
         */
        $image = $this->bathroom->images()->new();
        $servise = $image->imageService();
        $storeDir = $servise->storeDir();
        exec("find $storeDir -delete -type f -regex '.*\.\(png\|jpeg\|jpg\)'");
    }
    public function test_should_create_new_bathroomImage(): void
    {
        $this->assertEquals(0, count(BathroomImage::all()));
        /**
         * @var ImageModel $image
         */
        $image = $this->bathroom->images()->new();
        $image_servise = $image->imageService();
        $image_servise->upload([
          'name' => $this->test_image_name,
          'tmp_name' => $this->test_image,
          'size' => $this->test_image_size,
          'type' => $this->test_image_mime_type
        ]);
        $this->assertEquals(1, count(BathroomImage::all()));
        $this->assertFileExists($image_servise->getAbsoluteSavedFilePath());
    }

    public function test_cannot_persist_image_when_invalid_type(): void
    {
        $this->assertEquals(0, count(BathroomImage::all()));
        /**
         * @var ImageModel $image
         */
        $image = $this->bathroom->images()->new();
        $image_servise = $image->imageService();
        $image_servise->upload([
          'name' => $this->test_image_name,
          'tmp_name' => $this->test_image,
          'size' => $this->test_image_size,
          'type' => 'image/gif'
        ]);
        $this->assertEquals(0, count(BathroomImage::all()));
    }

    public function test_cannot_persist_image_when_invalid_name(): void
    {
        $this->assertEquals(0, count(BathroomImage::all()));
        /**
         * @var ImageModel $image
         */
        $image = $this->bathroom->images()->new();
        $image_servise = $image->imageService();
        $image_servise->upload([
          'name' => '',
          'tmp_name' => $this->test_image,
          'size' => $this->test_image_size,
          'type' => $this->test_image_mime_type
        ]);
        $this->assertEquals(0, count(BathroomImage::all()));
    }
    public function test_cannot_persist_image_when_ziro_size(): void
    {
        $this->assertEquals(0, count(BathroomImage::all()));
        /**
         * @var ImageModel $image
         */
        $image = $this->bathroom->images()->new();
        $image_servise = $image->imageService();
        $image_servise->upload([
          'name' => $this->test_image_name,
          'tmp_name' => $this->test_image,
          'size' => 0,
          'type' => $this->test_image_mime_type
        ]);
        $this->assertEquals(0, count(BathroomImage::all()));
    }

    public function test_cannot_persist_image_when_vary_lage(): void
    {
        $this->assertEquals(0, count(BathroomImage::all()));
        /**
         * @var ImageModel $image
         */
        $image = $this->bathroom->images()->new();
        $image_servise = $image->imageService();
        $image_servise->upload([
          'name' => $this->test_image_name,
          'tmp_name' => $this->test_image,
          'size' => ImageModel::MAX_IMAGE_ACEPTED_SIZE + 1,
          'type' => $this->test_image_mime_type
        ]);
        $this->assertEquals(0, count(BathroomImage::all()));
    }
}
