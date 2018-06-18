<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DwProjectApiTest extends TestCase
{
    use MakeDwProjectTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDwProject()
    {
        $dwProject = $this->fakeDwProjectData();
        $this->json('POST', '/api/v1/dwProjects', $dwProject);

        $this->assertApiResponse($dwProject);
    }

    /**
     * @test
     */
    public function testReadDwProject()
    {
        $dwProject = $this->makeDwProject();
        $this->json('GET', '/api/v1/dwProjects/'.$dwProject->id);

        $this->assertApiResponse($dwProject->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDwProject()
    {
        $dwProject = $this->makeDwProject();
        $editedDwProject = $this->fakeDwProjectData();

        $this->json('PUT', '/api/v1/dwProjects/'.$dwProject->id, $editedDwProject);

        $this->assertApiResponse($editedDwProject);
    }

    /**
     * @test
     */
    public function testDeleteDwProject()
    {
        $dwProject = $this->makeDwProject();
        $this->json('DELETE', '/api/v1/dwProjects/'.$dwProject->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/dwProjects/'.$dwProject->id);

        $this->assertResponseStatus(404);
    }
}
