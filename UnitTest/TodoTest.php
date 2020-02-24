<?php
use PHPUnit\Framework\TestCase;

class ToDoTest extends TestCase
{
    private $http;
    private $itemTest = [
        "title"       => "Work 1",
        "description" => "Defines the columns and rows of the grid with a space-separated list of values.",
        "start"       => "2020-01-27T13:30:00.000Z",
        "end"         => "2020-02-01T13:30:00.000Z",
        "status"      => "Planning",
    ];
    private $dataTestFail = [
        "title"       => "",
        "description" => "",
        "start"       => "2020-01-27T13:30:00.000Z",
        "end"         => "2020-01-01T13:30:00.000Z",
        "status"      => "",
    ];

    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testApiListSuccess()
    {
        $response    = $this->http->request('GET', '/api-list');
        $body        = (array) json_decode($response->getBody()->getContents());
        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertArrayHasKey('data', $body);
        $this->assertTrue(is_array($body['data']));
    }

    public function testApiCreateSuccess()
    {
        $response = $this->http->request('POST', '/api-create', [
            'body'         => json_encode($this->itemTest),
            'Content-Type' => 'application/json',
        ]);
        $body        = (array) json_decode($response->getBody()->getContents());
        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('data', $body);
        $this->assertTrue(is_array((array) $body['data']));

        //after run test then delete item test
        $this->deleteAfterTest(((array) $body['data'])['id']);
    }

    public function testApiCreateFail()
    {
        $response = $this->http->request('POST', '/api-create', [
            'body'         => json_encode($this->dataTestFail),
            'Content-Type' => 'application/json',
        ]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $body        = (array) json_decode($response->getBody()->getContents());
        $error       = (array) $body['error'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertEquals(422, $body['status']);
        $this->assertArrayHasKey('title', $error);
        $this->assertArrayHasKey('description', $error);
        $this->assertArrayHasKey('start-end', $error);
        $this->assertArrayHasKey('status', $error);

    }

    public function testApiUpdateSucess()
    {
        $idUpdate            = $this->createItemTest();
        $dataUpdate          = $this->itemTest;
        $dataUpdate['id']    = $idUpdate;
        $dataUpdate['title'] = 'Test Update';
        $response            = $this->http->request('POST', '/api-update', [
            'body'         => json_encode($dataUpdate),
            'Content-Type' => 'application/json',
        ]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $body        = (array) json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('data', $body);
        $this->assertTrue(is_array((array) $body['data']));

        $this->deleteAfterTest($idUpdate);
    }

    public function testApiUpdateFailWhenEmptyId()
    {
        $idUpdate            = $this->createItemTest();
        $dataUpdate          = $this->itemTest;
        $dataUpdate['title'] = 'Test Update';
        $response            = $this->http->request('POST', '/api-update', [
            'body'         => json_encode($dataUpdate),
            'Content-Type' => 'application/json',
        ]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $body        = (array) json_decode($response->getBody()->getContents());
        $error       = (array) $body['error'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertEquals(422, $body['status']);
        $this->assertArrayHasKey('id', $error);

        $this->deleteAfterTest($idUpdate);
    }

    public function testApiUpdateFailWhenValidateFail()
    {
        $dataTestFail       = $this->dataTestFail;
        $idUpdate           = $this->createItemTest();
        $dataTestFail['id'] = $idUpdate;
        $response           = $this->http->request('POST', '/api-update', [
            'body'         => json_encode($dataTestFail),
            'Content-Type' => 'application/json',
        ]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $body        = (array) json_decode($response->getBody()->getContents());
        $error       = (array) $body['error'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertEquals(422, $body['status']);
        $this->assertArrayHasKey('error', $body);
        $this->assertArrayHasKey('title', $error);
        $this->assertArrayHasKey('description', $error);
        $this->assertArrayHasKey('start-end', $error);
        $this->assertArrayHasKey('status', $error);

        $this->deleteAfterTest($idUpdate);
    }

    public function testDestroySuccess()
    {
        $idUpdate = $this->createItemTest();

        $response = $this->http->request('POST', '/api-destroy', [
            'body'         => json_encode(['id' => $idUpdate]),
            'Content-Type' => 'application/json',
        ]);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $body        = (array) json_decode($response->getBody()->getContents());
        $data        = (array) $body['data'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertArrayHasKey('status', $body);
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('id', $data);

    }

    public function testDestroyFail()
    {
        $response = $this->http->request('POST', '/api-destroy', [
            'body'         => json_encode(['id' => '']),
            'Content-Type' => 'application/json',
        ]);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $body        = (array) json_decode($response->getBody()->getContents());
        $error       = (array) $body['error'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(422, $body['status']);
        $this->assertArrayHasKey('id', $error);
    }

    //create befor test update
    private function createItemTest()
    {
        $response = $this->http->request('POST', '/api-create', [
            'body'         => json_encode($this->itemTest),
            'Content-Type' => 'application/json',
        ]);
        $body = (array) json_decode($response->getBody()->getContents());

        return ((array) $body['data'])['id'];
    }

    //destroy after create test

    private function deleteAfterTest($id)
    {
        $this->http->request('POST', '/api-destroy', [
            'body'         => json_encode(['id' => $id]),
            'Content-Type' => 'application/json',
        ]);
    }
}
