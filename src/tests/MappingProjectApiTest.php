<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MappingProjectApiTest extends TestCase
{
    use MakeMappingProjectTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMappingProject()
    {
        $mappingProject = $this->fakeMappingProjectData();
        $this->json('POST', '/api/v1/mappingProjects', $mappingProject);

        $this->assertApiResponse($mappingProject);
    }

    /**
     * @test
     */
    public function testReadMappingProject()
    {
        $mappingProject = $this->makeMappingProject();
        $this->json('GET', '/api/v1/mappingProjects/'.$mappingProject->id);

        $this->assertApiResponse($mappingProject->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMappingProject()
    {
        $mappingProject = $this->makeMappingProject();
        $editedMappingProject = $this->fakeMappingProjectData();

        $this->json('PUT', '/api/v1/mappingProjects/'.$mappingProject->id, $editedMappingProject);

        $this->assertApiResponse($editedMappingProject);
    }

    /**
     * @test
     */
    public function testDeleteMappingProject()
    {
        $mappingProject = $this->makeMappingProject();
        $this->json('DELETE', '/api/v1/mappingProjects/'.$mappingProject->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/mappingProjects/'.$mappingProject->id);

        $this->assertResponseStatus(404);
    }
}
