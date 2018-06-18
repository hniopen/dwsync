<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DwQuestionApiTest extends TestCase
{
    use MakeDwQuestionTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDwQuestion()
    {
        $dwQuestion = $this->fakeDwQuestionData();
        $this->json('POST', '/api/v1/dwQuestions', $dwQuestion);

        $this->assertApiResponse($dwQuestion);
    }

    /**
     * @test
     */
    public function testReadDwQuestion()
    {
        $dwQuestion = $this->makeDwQuestion();
        $this->json('GET', '/api/v1/dwQuestions/'.$dwQuestion->id);

        $this->assertApiResponse($dwQuestion->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDwQuestion()
    {
        $dwQuestion = $this->makeDwQuestion();
        $editedDwQuestion = $this->fakeDwQuestionData();

        $this->json('PUT', '/api/v1/dwQuestions/'.$dwQuestion->id, $editedDwQuestion);

        $this->assertApiResponse($editedDwQuestion);
    }

    /**
     * @test
     */
    public function testDeleteDwQuestion()
    {
        $dwQuestion = $this->makeDwQuestion();
        $this->json('DELETE', '/api/v1/dwQuestions/'.$dwQuestion->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/dwQuestions/'.$dwQuestion->id);

        $this->assertResponseStatus(404);
    }
}
