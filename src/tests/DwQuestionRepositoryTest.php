<?php

use Hni\Dwsync\Models\DwQuestion;
use Hni\Dwsync\Repositories\DwQuestionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DwQuestionRepositoryTest extends TestCase
{
    use MakeDwQuestionTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DwQuestionRepository
     */
    protected $dwQuestionRepo;

    public function setUp()
    {
        parent::setUp();
        $this->dwQuestionRepo = App::make(DwQuestionRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDwQuestion()
    {
        $dwQuestion = $this->fakeDwQuestionData();
        $createdDwQuestion = $this->dwQuestionRepo->create($dwQuestion);
        $createdDwQuestion = $createdDwQuestion->toArray();
        $this->assertArrayHasKey('id', $createdDwQuestion);
        $this->assertNotNull($createdDwQuestion['id'], 'Created DwQuestion must have id specified');
        $this->assertNotNull(DwQuestion::find($createdDwQuestion['id']), 'DwQuestion with given id must be in DB');
        $this->assertModelData($dwQuestion, $createdDwQuestion);
    }

    /**
     * @test read
     */
    public function testReadDwQuestion()
    {
        $dwQuestion = $this->makeDwQuestion();
        $dbDwQuestion = $this->dwQuestionRepo->find($dwQuestion->id);
        $dbDwQuestion = $dbDwQuestion->toArray();
        $this->assertModelData($dwQuestion->toArray(), $dbDwQuestion);
    }

    /**
     * @test update
     */
    public function testUpdateDwQuestion()
    {
        $dwQuestion = $this->makeDwQuestion();
        $fakeDwQuestion = $this->fakeDwQuestionData();
        $updatedDwQuestion = $this->dwQuestionRepo->update($fakeDwQuestion, $dwQuestion->id);
        $this->assertModelData($fakeDwQuestion, $updatedDwQuestion->toArray());
        $dbDwQuestion = $this->dwQuestionRepo->find($dwQuestion->id);
        $this->assertModelData($fakeDwQuestion, $dbDwQuestion->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDwQuestion()
    {
        $dwQuestion = $this->makeDwQuestion();
        $resp = $this->dwQuestionRepo->delete($dwQuestion->id);
        $this->assertTrue($resp);
        $this->assertNull(DwQuestion::find($dwQuestion->id), 'DwQuestion should not exist in DB');
    }
}
