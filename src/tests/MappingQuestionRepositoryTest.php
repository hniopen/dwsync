<?php

use Hni\Dwsync\Models\MappingQuestion;
use Hni\Dwsync\Repositories\MappingQuestionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MappingQuestionRepositoryTest extends TestCase
{
    use MakeMappingQuestionTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MappingQuestionRepository
     */
    protected $mappingQuestionRepo;

    public function setUp()
    {
        parent::setUp();
        $this->mappingQuestionRepo = App::make(MappingQuestionRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMappingQuestion()
    {
        $mappingQuestion = $this->fakeMappingQuestionData();
        $createdMappingQuestion = $this->mappingQuestionRepo->create($mappingQuestion);
        $createdMappingQuestion = $createdMappingQuestion->toArray();
        $this->assertArrayHasKey('id', $createdMappingQuestion);
        $this->assertNotNull($createdMappingQuestion['id'], 'Created MappingQuestion must have id specified');
        $this->assertNotNull(MappingQuestion::find($createdMappingQuestion['id']), 'MappingQuestion with given id must be in DB');
        $this->assertModelData($mappingQuestion, $createdMappingQuestion);
    }

    /**
     * @test read
     */
    public function testReadMappingQuestion()
    {
        $mappingQuestion = $this->makeMappingQuestion();
        $dbMappingQuestion = $this->mappingQuestionRepo->find($mappingQuestion->id);
        $dbMappingQuestion = $dbMappingQuestion->toArray();
        $this->assertModelData($mappingQuestion->toArray(), $dbMappingQuestion);
    }

    /**
     * @test update
     */
    public function testUpdateMappingQuestion()
    {
        $mappingQuestion = $this->makeMappingQuestion();
        $fakeMappingQuestion = $this->fakeMappingQuestionData();
        $updatedMappingQuestion = $this->mappingQuestionRepo->update($fakeMappingQuestion, $mappingQuestion->id);
        $this->assertModelData($fakeMappingQuestion, $updatedMappingQuestion->toArray());
        $dbMappingQuestion = $this->mappingQuestionRepo->find($mappingQuestion->id);
        $this->assertModelData($fakeMappingQuestion, $dbMappingQuestion->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMappingQuestion()
    {
        $mappingQuestion = $this->makeMappingQuestion();
        $resp = $this->mappingQuestionRepo->delete($mappingQuestion->id);
        $this->assertTrue($resp);
        $this->assertNull(MappingQuestion::find($mappingQuestion->id), 'MappingQuestion should not exist in DB');
    }
}
