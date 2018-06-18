<?php

use Hni\Dwsync\Models\MappingProject;
use Hni\Dwsync\Repositories\MappingProjectRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MappingProjectRepositoryTest extends TestCase
{
    use MakeMappingProjectTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MappingProjectRepository
     */
    protected $mappingProjectRepo;

    public function setUp()
    {
        parent::setUp();
        $this->mappingProjectRepo = App::make(MappingProjectRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMappingProject()
    {
        $mappingProject = $this->fakeMappingProjectData();
        $createdMappingProject = $this->mappingProjectRepo->create($mappingProject);
        $createdMappingProject = $createdMappingProject->toArray();
        $this->assertArrayHasKey('id', $createdMappingProject);
        $this->assertNotNull($createdMappingProject['id'], 'Created MappingProject must have id specified');
        $this->assertNotNull(MappingProject::find($createdMappingProject['id']), 'MappingProject with given id must be in DB');
        $this->assertModelData($mappingProject, $createdMappingProject);
    }

    /**
     * @test read
     */
    public function testReadMappingProject()
    {
        $mappingProject = $this->makeMappingProject();
        $dbMappingProject = $this->mappingProjectRepo->find($mappingProject->id);
        $dbMappingProject = $dbMappingProject->toArray();
        $this->assertModelData($mappingProject->toArray(), $dbMappingProject);
    }

    /**
     * @test update
     */
    public function testUpdateMappingProject()
    {
        $mappingProject = $this->makeMappingProject();
        $fakeMappingProject = $this->fakeMappingProjectData();
        $updatedMappingProject = $this->mappingProjectRepo->update($fakeMappingProject, $mappingProject->id);
        $this->assertModelData($fakeMappingProject, $updatedMappingProject->toArray());
        $dbMappingProject = $this->mappingProjectRepo->find($mappingProject->id);
        $this->assertModelData($fakeMappingProject, $dbMappingProject->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMappingProject()
    {
        $mappingProject = $this->makeMappingProject();
        $resp = $this->mappingProjectRepo->delete($mappingProject->id);
        $this->assertTrue($resp);
        $this->assertNull(MappingProject::find($mappingProject->id), 'MappingProject should not exist in DB');
    }
}
