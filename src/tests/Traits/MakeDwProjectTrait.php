<?php

use Faker\Factory as Faker;
use Hni\Dwsync\Models\DwProject;
use Hni\Dwsync\Repositories\DwProjectRepository;

trait MakeDwProjectTrait
{
    /**
     * Create fake instance of DwProject and save it in database
     *
     * @param array $dwProjectFields
     * @return DwProject
     */
    public function makeDwProject($dwProjectFields = [])
    {
        /** @var DwProjectRepository $dwProjectRepo */
        $dwProjectRepo = App::make(DwProjectRepository::class);
        $theme = $this->fakeDwProjectData($dwProjectFields);
        return $dwProjectRepo->create($theme);
    }

    /**
     * Get fake instance of DwProject
     *
     * @param array $dwProjectFields
     * @return DwProject
     */
    public function fakeDwProject($dwProjectFields = [])
    {
        return new DwProject($this->fakeDwProjectData($dwProjectFields));
    }

    /**
     * Get fake data of DwProject
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDwProjectData($dwProjectFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'questCode' => $fake->word,
            'submissionTable' => $fake->word,
            'parentId' => $fake->randomDigitNotNull,
            'comment' => $fake->text,
            'isDisplayed' => $fake->word,
            'xformUrl' => $fake->word,
            'credential' => $fake->word,
            'entityType' => $fake->word,
            'formType' => $fake->word
        ], $dwProjectFields);
    }
}
