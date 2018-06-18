<?php

use Hni\Dwsync\Models\DwProject;
use Hni\Dwsync\Repositories\DwProjectRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DwProjectRepositoryTest extends TestCase
{
    use MakeDwProjectTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DwProjectRepository
     */
    protected $dwProjectRepo;

    public function setUp()
    {
        parent::setUp();
        $this->dwProjectRepo = App::make(DwProjectRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDwProject()
    {
        $dwProject = $this->fakeDwProjectData();
        $createdDwProject = $this->dwProjectRepo->create($dwProject);
        $createdDwProject = $createdDwProject->toArray();
        $this->assertArrayHasKey('id', $createdDwProject);
        $this->assertNotNull($createdDwProject['id'], 'Created DwProject must have id specified');
        $this->assertNotNull(DwProject::find($createdDwProject['id']), 'DwProject with given id must be in DB');
        $this->assertModelData($dwProject, $createdDwProject);
    }

    /**
     * @test read
     */
    public function testReadDwProject()
    {
        $dwProject = $this->makeDwProject();
        $dbDwProject = $this->dwProjectRepo->find($dwProject->id);
        $dbDwProject = $dbDwProject->toArray();
        $this->assertModelData($dwProject->toArray(), $dbDwProject);
    }

    /**
     * @test update
     */
    public function testUpdateDwProject()
    {
        $dwProject = $this->makeDwProject();
        $fakeDwProject = $this->fakeDwProjectData();
        $updatedDwProject = $this->dwProjectRepo->update($fakeDwProject, $dwProject->id);
        $this->assertModelData($fakeDwProject, $updatedDwProject->toArray());
        $dbDwProject = $this->dwProjectRepo->find($dwProject->id);
        $this->assertModelData($fakeDwProject, $dbDwProject->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDwProject()
    {
        $dwProject = $this->makeDwProject();
        $resp = $this->dwProjectRepo->delete($dwProject->id);
        $this->assertTrue($resp);
        $this->assertNull(DwProject::find($dwProject->id), 'DwProject should not exist in DB');
    }
}
