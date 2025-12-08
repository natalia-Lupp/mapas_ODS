<?php

namespace Tests\Unit\ApiRest;

use Core\Database\Database;
use Core\Router\Router;
use CurlHandle;
use Tests\TestCase;

use function PHPUnit\Framework\isString;

class ConsumptionTest extends TestCase
{
    protected const string PASSWOERD = 'SenhaSenha1';
    protected const string USER = 'user1@email.com';
    protected ?CurlHandle $ch = null;
    protected const string HOST = 'http://web:8080';
    public function setUp(): void
    {
        parent::setUp();
        Database::populate();
        Router::loadRoutes();
        $this->ch = curl_init();
    }
    public function tearDown(): void
    {
        parent::tearDown();
        curl_close($this->ch);
        $this->ch = null;
    }

    public function test_consumptions_by_bathroon_success(): void
    {
        $url = self::HOST . route(
            'api.admin.buildings.bathrooms.consumptions.index',
            ['building_id' => 1, 'bathroom_id' => 1]
        );
        $options = [
          CURLOPT_URL => $url,
          CURLOPT_HTTPHEADER => [
               'Authorization: Basic ' . base64_encode(self::USER . ':' . self::PASSWOERD),
               'Content-Type: application/json'
            ],
          CURLOPT_RETURNTRANSFER => true
        ];
        curl_setopt_array($this->ch, $options);
        $response = curl_exec($this->ch);
        $this->assertIsString($response);
        $response = json_decode($response, true);
        $this->assertEquals(2, count($response));
        $this->assertEquals('torneira de lavado', $response[0]['name']);
        $this->assertEquals('Vaso sanitário', $response[1]['name']);
        $this->assertEquals(350, $response[0]['quantity']);
        $this->assertEquals(250, $response[1]['quantity']);
        $this->assertEquals('2025-11-05', $response[0]['date']);
        $this->assertEquals('2025-11-05', $response[1]['date']);
        $this->assertEquals(4, count($response[0]));
        $this->assertEquals(4, count($response[1]));
    }

    public function test_consumptions_by_bathroon_fail(): void
    {
        $url = self::HOST . route(
            'api.admin.buildings.bathrooms.consumptions.index',
            ['building_id' => 1, 'bathroom_id' => 140]
        );
        $options = [
          CURLOPT_URL => $url,
          CURLOPT_HTTPHEADER => [
               'Authorization: Basic ' . base64_encode(self::USER . ':' . self::PASSWOERD),
               'content-type: application/json'
            ],
          CURLOPT_RETURNTRANSFER => true
        ];
        curl_setopt_array($this->ch, $options);
        $response = curl_exec($this->ch);
        $this->assertIsString($response);
        $response = json_decode($response, true);
        $this->assertEquals(2, count($response));
        $this->assertEquals('Bathroom not found!', $response['message']);
        $this->assertEquals(422, $response['code']);
        $this->assertEquals(422, curl_getinfo($this->ch, CURLINFO_HTTP_CODE));
    }

    public function test_basic_authentication(): void
    {
        $url = self::HOST . route(
            'api.admin.buildings.bathrooms.consumptions.index',
            ['building_id' => 1, 'bathroom_id' => 1]
        );
        $options = [
          CURLOPT_URL => $url,
          CURLOPT_HTTPHEADER => [
               'Authorization: Basic ' . base64_encode('user5@email.com' . ':' . self::PASSWOERD),
               'Content-Type: application/json'
            ],
          CURLOPT_RETURNTRANSFER => true
        ];
        curl_setopt_array($this->ch, $options);
        $response = curl_exec($this->ch);
        $errors = curl_error($this->ch);
        $this->assertEquals('{"message":"Acesso negado!","code":401}', $response);
        $this->assertEquals(401, curl_getinfo($this->ch, CURLINFO_HTTP_CODE));
    }
}
