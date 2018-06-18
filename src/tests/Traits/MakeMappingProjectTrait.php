<?php

use Faker\Factory as Faker;
use Hni\Dwsync\Models\MappingProject;
use Hni\Dwsync\Repositories\MappingProjectRepository;

trait MakeMappingProjectTrait
{
    /**
     * Create fake instance of MappingProject and save it in database
     *
     * @param array $mappingProjectFields
     * @return MappingProject
     */
    public function makeMappingProject($mappingProjectFields = [])
    {
        /** @var MappingProjectRepository $mappingProjectRepo */
        $mappingProjectRepo = App::make(MappingProjectRepository::class);
        $theme = $this->fakeMappingProjectData($mappingProjectFields);
        return $mappingProjectRepo->create($theme);
    }

    /**
     * Get fake instance of MappingProject
     *
     * @param array $mappingProjectFields
     * @return MappingProject
     */
    public function fakeMappingProject($mappingProjectFields = [])
    {
        return new MappingProject($this->fakeMappingProjectData($mappingProjectFields));
    }

    /**
     * Get fake data of MappingProject
     *
     * @param array $postFields
     * @return array
     */
    public function fakeMappingProjectData($mappingProjectFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'project1' => $fake->randomDigitNotNull,
            'project2' => $fake->randomDigitNotNull,
            'dateLastExported' => $fake->word,
            'isActive' => $fake->word
        ], $mappingProjectFields);
    }
}
