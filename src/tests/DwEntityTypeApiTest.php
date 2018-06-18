<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DwEntityTypeApiTest extends TestCase
{
    use MakeDwEntityTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDwEntityType()
    {
        $dwEntityType = $this->fakeDwEntityTypeData();
        $this->json('POST', '/api/v1/dwEntityTypes', $dwEntityType);

        $this->assertApiResponse($dwEntityType);
    }

    /**
     * @test
     */
    public function testReadDwEntityType()
    {
        $dwEntityType = $this->makeDwEntityType();
        $this->json('GET', '/api/v1/dwEntityTypes/'.$dwEntityType->id);

        $this->assertApiResponse($dwEntityType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDwEntityType()
    {
        $dwEntityType = $this->makeDwEntityType();
        $editedDwEntityType = $this->fakeDwEntityTypeData();

        $this->json('PUT', '/api/v1/dwEntityTypes/'.$dwEntityType->id, $editedDwEntityType);

        $this->assertApiResponse($editedDwEntityType);
    }

    /**
     * @test
     */
    public function testDeleteDwEntityType()
    {
        $dwEntityType = $this->makeDwEntityType();
        $this->json('DELETE', '/api/v1/dwEntityTypes/'.$dwEntityType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/dwEntityTypes/'.$dwEntityType->id);

        $this->assertResponseStatus(404);
    }
}
