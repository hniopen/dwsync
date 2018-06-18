<?php

use Faker\Factory as Faker;
use Hni\Dwsync\Models;
use Hni\Dwsync\Repositories\MappingQuestionRepository;

trait MakeMappingQuestionTrait
{
    /**
     * Create fake instance of MappingQuestion and save it in database
     *
     * @param array $mappingQuestionFields
     * @return MappingQuestion
     */
    public function makeMappingQuestion($mappingQuestionFields = [])
    {
        /** @var MappingQuestionRepository $mappingQuestionRepo */
        $mappingQuestionRepo = App::make(MappingQuestionRepository::class);
        $theme = $this->fakeMappingQuestionData($mappingQuestionFields);
        return $mappingQuestionRepo->create($theme);
    }

    /**
     * Get fake instance of MappingQuestion
     *
     * @param array $mappingQuestionFields
     * @return MappingQuestion
     */
    public function fakeMappingQuestion($mappingQuestionFields = [])
    {
        return new MappingQuestion($this->fakeMappingQuestionData($mappingQuestionFields));
    }

    /**
     * Get fake data of MappingQuestion
     *
     * @param array $postFields
     * @return array
     */
    public function fakeMappingQuestionData($mappingQuestionFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'mappingProjectId' => $fake->randomDigitNotNull,
            'question1' => $fake->randomDigitNotNull,
            'question2' => $fake->randomDigitNotNull,
            'functions' => $fake->word,
            'arg1' => $fake->word,
            'arg2' => $fake->word
        ], $mappingQuestionFields);
    }
}
