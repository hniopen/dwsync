<?php

use Faker\Factory as Faker;
use Hni\Dwsync\Models\DwQuestion;
use Hni\Dwsync\Repositories\DwQuestionRepository;

trait MakeDwQuestionTrait
{
    /**
     * Create fake instance of DwQuestion and save it in database
     *
     * @param array $dwQuestionFields
     * @return DwQuestion
     */
    public function makeDwQuestion($dwQuestionFields = [])
    {
        /** @var DwQuestionRepository $dwQuestionRepo */
        $dwQuestionRepo = App::make(DwQuestionRepository::class);
        $theme = $this->fakeDwQuestionData($dwQuestionFields);
        return $dwQuestionRepo->create($theme);
    }

    /**
     * Get fake instance of DwQuestion
     *
     * @param array $dwQuestionFields
     * @return DwQuestion
     */
    public function fakeDwQuestion($dwQuestionFields = [])
    {
        return new DwQuestion($this->fakeDwQuestionData($dwQuestionFields));
    }

    /**
     * Get fake data of DwQuestion
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDwQuestionData($dwQuestionFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'projectId' => $fake->randomDigitNotNull,
            'xformQuestionId' => $fake->word,
            'questionId' => $fake->word,
            'path' => $fake->word,
            'labelDefault' => $fake->text,
            'labelFr' => $fake->text,
            'labelUs' => $fake->text,
            'dataType' => $fake->word,
            'dataFormat' => $fake->word,
            'order' => $fake->randomDigitNotNull,
            'linkedIdnr' => $fake->word,
            'periodType' => $fake->word,
            'periodTypeFormat' => $fake->word,
            'isUnique' => $fake->word,
            'isMigrated' => $fake->word
        ], $dwQuestionFields);
    }
}
