<?php
declare(strict_types=1);

namespace Tests\Plan\Presentation\Rest\V1;

use Faker\Factory as FakerFactory;
use GuzzleHttp\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
class E2EGetAllOsTest extends WebTestCase
{
    protected static Client $client;
    protected static array $USER_LOGIN;
    protected static string $JWT;

    protected static $uri = '/api/v1';
    protected static $resource = '/os';
    public static function setUpBeforeClass(): void
    {
        self::$USER_LOGIN = [
            "email" => "admin@admin.com",
            "password" => "password",
        ];
        self::login();
        self::$client = new Client(['base_uri' => 'http://localhost:80', 'headers'=>['Authorization'=>'Bearer '.self::$JWT]]);
    }
    public static function login():void
    {
        $client = new Client(['base_uri'=>'http://localhost:80']);
        $result = $client->post('/api/v1/auth/login',['json'=>self::$USER_LOGIN]);
        self::$JWT =   json_decode($result->getBody()->getContents())->token;
    }

    public function testGetAllPlanOK(): void
    {
        try {
            $response = self::$client->get('/api/v1/os');
        
            self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
            $oss = json_decode($response->getBody()->getContents(), true);
        
            self::assertIsArray($oss);
            self::assertNotEmpty($oss, 'OS list is empty');
        
            foreach ($oss as $os) {
                self::assertArrayHasKey('uuid', $os);
                self::assertArrayHasKey('name', $os);
                self::assertArrayHasKey('tag', $os);
                self::assertArrayHasKey('image', $os);
            }
        
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
        
            self::assertEquals(Response::HTTP_NOT_FOUND, $statusCode);
            self::assertArrayHasKey('error', $body);
            self::assertStringContainsString('ListOsEmptyException', $body['error']);
        }
    }
    // php bin/phpunit tests/Proxmox/Os/Rest/V1/E2EGetAllOsTest.php
}