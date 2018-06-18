<?php

use Hni\Dwsync\Models\DwEntityType;
use Hni\Dwsync\Repositories\DwEntityTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DwEntityTypeRepositoryTest extends TestCase
{
    use MakeDwEntityTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DwEntityTypeRepository
     */
    protected $dwEntityTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->dwEntityTypeRepo = App::make(DwEntityTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDwEntityType()
    {
        $dwEntityType = $this->fakeDwEntityTypeData();
        $createdDwEntityType = $this->dwEntityTypeRepo->create($dwEntityType);
        $createdDwEntityType = $createdDwEntityType->toArray();
        $this->assertArrayHasKey('id', $createdDwEntityType);
        $this->assertNotNull($createdDwEntityType['id'], 'Created DwEntityType must have id specified');
        $this->assertNotNull(DwEntityType::find($createdDwEntityType['id']), 'DwEntityType with given id must be in DB');
        $this->assertModelData($dwEntityType, $createdDwEntityType);
    }

    /**
     * @test read
     */
    public function testReadDwEntityType()
    {
        $dwEntityType = $this->makeDwEntityType();
        $dbDwEntityType = $this->dwEntityTypeRepo->find($dwEntityType->id);
        $dbDwEntityType = $dbDwEntityType->toArray();
        $this->assertModelData($dwEntityType->toArray(), $dbDwEntityType);
    }

    /**
     * @test update
     */
    public function testUpdateDwEntityType()
    {
        $dwEntityType = $this->makeDwEntityType();
        $fakeDwEntityType = $this->fakeDwEntityTypeData();
        $updatedDwEntityType = $this->dwEntityTypeRepo->update($fakeDwEntityType, $dwEntityType->id);
        $this->assertModelData($fakeDwEntityType, $updatedDwEntityType->toArray());
        $dbDwEntityType = $this->dwEntityTypeRepo->find($dwEntityType->id);
        $this->assertModelData($fakeDwEntityType, $dbDwEntityType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDwEntityType()
    {
        $dwEntityType = $this->makeDwEntityType();
        $resp = $this->dwEntityTypeRepo->delete($dwEntityType->id);
        $this->assertTrue($resp);
        $this->assertNull(DwEntityType::find($dwEntityType->id), 'DwEntityType should not exist in DB');
    }
}
