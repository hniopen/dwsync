<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MappingQuestionApiTest extends TestCase
{
    use MakeMappingQuestionTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMappingQuestion()
    {
        $mappingQuestion = $this->fakeMappingQuestionData();
        $this->json('POST', '/api/v1/mappingQuestions', $mappingQuestion);

        $this->assertApiResponse($mappingQuestion);
    }

    /**
     * @test
     */
    public function testReadMappingQuestion()
    {
        $mappingQuestion = $this->makeMappingQuestion();
        $this->json('GET', '/api/v1/mappingQuestions/'.$mappingQuestion->id);

        $this->assertApiResponse($mappingQuestion->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMappingQuestion()
    {
        $mappingQuestion = $this->makeMappingQuestion();
        $editedMappingQuestion = $this->fakeMappingQuestionData();

        $this->json('PUT', '/api/v1/mappingQuestions/'.$mappingQuestion->id, $editedMappingQuestion);

        $this->assertApiResponse($editedMappingQuestion);
    }

    /**
     * @test
     */
    public function testDeleteMappingQuestion()
    {
        $mappingQuestion = $this->makeMappingQuestion();
        $this->json('DELETE', '/api/v1/mappingQuestions/'.$mappingQuestion->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/mappingQuestions/'.$mappingQuestion->id);

        $this->assertResponseStatus(404);
    }
}
