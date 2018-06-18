<?php

use Hni\Dwsync\Models\DwProject;
use Hni\Dwsync\Models\DwQuestion;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

use phpmock\MockBuilder;

class DwEntityProjectTest extends TestCase
{

    public $dwProject;

    public static function setUpBeforeClass() {
        $myConfig = function() {
            return null;
        };
        $builder = new MockBuilder();
        $modelsNamespace = $builder->setNamespace('Hni\Dwsync\Models');
        $modelsNamespace->setName('config')
            ->setFunction($myConfig);
        $mock = $builder->build();
        $mock->enable();
        runkit_method_rename(get_class(new DwProject()), 'getQuestionForColumns', 'oldGetQuestionsForColumns');
        runkit_method_add(
            get_class(new DwProject()), 'getQuestionForColumns', '$uniqueColumns', '$dwQuestion=new stdClass();$dwQuestion->type="foo";$dwQuestion->label="bar";$dwQuestion->questionId=$uniqueColumns["questionId"];return $dwQuestion;'
        );
        $mock->disable();
    }
    public function setUp() {
        parent::setUp();
        $myConfig = function() {
            return null;
        };
        $builder = new MockBuilder();
        $modelsNamespace = $builder->setNamespace('Hni\Dwsync\Models');
        $modelsNamespace->setName('config')
            ->setFunction($myConfig);
        $mock = $builder->build();
        $mock->enable();
        $this->dwProject = new DwProject(); 
        $mock->disable();

        $myFctGetDBTypeFromXls = function() {
            return "some type";
        };
        $value = array ( 0 => array ( 'crops' => 'Maize', 'field_size' => '4.0', 'harvest' => '900.0', ), 1 => array ( 'crops' => 'Common beans', 'field_size' => '0.25', 'harvest' => '10.0', ), 2 => array ( 'crops' => 'Common beans', 'field_size' => '0.25', 'harvest' => NULL, ), );

        $builder = new MockBuilder();
        $modelsNamespace = $builder->setNamespace('Hni\Dwsync\Models');
        $modelsNamespace->setName('fctGetDBTypeFromXls')
            ->setFunction($myFctGetDBTypeFromXls);
        $this->myFctGetDBTypeFromXlsRedefinition = $builder->build();
    }

    public function testSetCorrectQuestionsToBeAdded()
    {
        //1 is already created on questionnaire import
        $expectedResult = array (
            0 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__2__crops',
                'xformQuestionId' => '2__crops',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            1 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__2__field_size',
                'xformQuestionId' => '2__field_size',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            2 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__2__harvest',
                'xformQuestionId' => '2__harvest',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            3 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__3__crops',
                'xformQuestionId' => '3__crops',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            4 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__3__field_size',
                'xformQuestionId' => '3__field_size',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            5 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__3__harvest',
                'xformQuestionId' => '3__harvest',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
        );
        $myFctGetDBTypeFromXls = function() {
            return "some type";
        };
        $value = array ( 0 => array ( 'crops' => 'Maize', 'field_size' => '4.0', 'harvest' => '900.0', ), 1 => array ( 'crops' => 'Common beans', 'field_size' => '0.25', 'harvest' => '10.0', ), 2 => array ( 'crops' => 'Common beans', 'field_size' => '0.25', 'harvest' => NULL, ), );

        $this->myFctGetDBTypeFromXlsRedefinition->enable();
        $result = $this->dwProject->getNewQuestionsFromRepeatData($value, 'crop_production');
        $this->myFctGetDBTypeFromXlsRedefinition->disable();

        $this->assertEquals($result, $expectedResult);
    }
    public function testSetCorrectColumnsToBeAdded()
    {
        $questions = array (
            0 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__2__crops',
                'xformQuestionId' => '2__crops',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            1 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__2__field_size',
                'xformQuestionId' => '2__field_size',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            2 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__2__harvest',
                'xformQuestionId' => '2__harvest',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            3 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__3__crops',
                'xformQuestionId' => '3__crops',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            4 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__3__field_size',
                'xformQuestionId' => '3__field_size',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
            5 => 
            (object) array(
                'type' => 'foo',
                'label' => 'bar',
                'questionId' => 'crop_production__3__harvest',
                'xformQuestionId' => '3__harvest',
                'labelDefault' => 'bar',
                'dataType' => 'foo',
                'path' => 'crop_production',
            ),
        );
        //1 is already created on questionnaire import
        $expectedResult = array (
            'crop_production__2__crops' => 
            array (
                'type' => 'some type',
            ),
            'crop_production__2__field_size' => 
            array (
                'type' => 'some type',
            ),
            'crop_production__2__harvest' => 
            array (
                'type' => 'some type',
            ),
            'crop_production__3__crops' => 
            array (
                'type' => 'some type',
            ),
            'crop_production__3__field_size' => 
            array (
                'type' => 'some type',
            ),
            'crop_production__3__harvest' => 
            array (
                'type' => 'some type',
            ),
        );
        $this->myFctGetDBTypeFromXlsRedefinition->enable();
        $result = $this->dwProject->getNewColumnsFromQuestionsToAdd($questions);
        $this->myFctGetDBTypeFromXlsRedefinition->disable();
        $this->assertEquals($result, $expectedResult);
    }

    public function testReorderQuestionsForBeginRepeat() {
        $questions = array (
            0 => 
            (object) array(
                'questionId' => 'house',
                'xformQuestionId' => 'house',
                'dataType' => 'foo',
                'order' => 1,
                'path' => null,
            ),
            1 => 
            (object) array(
                'questionId' => 'crop_production',
                'xformQuestionId' => 'crop_production',
                'dataType' => 'begin_repeat',
                'order' => 2,
                'path' => null,
            ),
            2 => 
            (object) array(
                'questionId' => 'crop_production__1__crops',
                'xformQuestionId' => '1__crops',
                'dataType' => 'foo',
                'order' => 3,
                'path' => 'crop_production',
            ),
            3 => 
            (object) array(
                'questionId' => 'crop_production__1__field_size',
                'xformQuestionId' => '1__field_size',
                'dataType' => 'foo',
                'order' => 4,
                'path' => 'crop_production',
            ),
            4 => 
            (object) array(
                'questionId' => 'school',
                'xformQuestionId' => 'school',
                'dataType' => 'foo',
                'order' => 5,
                'path' => null,
            ),
            5 => 
            (object) array(
                'questionId' => 'school2',
                'xformQuestionId' => 'school2',
                'dataType' => 'begin_group',
                'order' => 6,
                'path' => null,
            ),
            6 => 
            (object) array(
                'questionId' => 'school2__school_sub_q',
                'xformQuestionId' => 'school_sub_q',
                'dataType' => 'begin_group',
                'order' => 7,
                'path' => 'school2',
            ),
            7 => 
            (object) array(
                'questionId' => 'school2__school_sub_q__school_district',
                'xformQuestionId' => 'school_district',
                'dataType' => 'foo',
                'order' => 8,
                'path' => 'school2/school_sub_q',
            ),
            8 => 
            (object) array(
                'questionId' => 'school2__school_sub_q__school_sub_sub_q',
                'xformQuestionId' => 'school_sub_sub_q',
                'dataType' => 'begin_group',
                'order' => 9,
                'path' => 'school2/school_sub_q',
            ),
            9 => 
            (object) array(
                'questionId' => 'school2__school_sub_q__school_sub_sub_q_final',
                'xformQuestionId' => 'final',
                'dataType' => 'foo',
                'order' => 10,
                'path' => 'school2/school_sub_q/school_sub_sub_q',
            ),
            10 => 
            (object) array(
                'questionId' => 'crop_production__2__crops',
                'xformQuestionId' => '2__crops',
                'dataType' => 'foo',
                'order' => 11,
                'path' => 'crop_production',
            ),
            11 => 
            (object) array(
                'questionId' => 'crop_production__2__field_size',
                'xformQuestionId' => '2__field_size',
                'dataType' => 'foo',
                'order' => 12,
                'path' => 'crop_production',
            ),
            12 => 
            (object) array(
                'questionId' => 'crop_production__3__crops',
                'xformQuestionId' => '3__crops',
                'dataType' => 'foo',
                'order' => 13,
                'path' => 'crop_production',
            ),
            13 => 
            (object) array(
                'questionId' => 'crop_production__3__field_size',
                'xformQuestionId' => '3__field_size',
                'dataType' => 'foo',
                'order' => 14,
                'path' => 'crop_production',
            ),
        );
        $expectedResult = array (
            0 => 
            (object) array(
                'questionId' => 'house',
                'xformQuestionId' => 'house',
                'dataType' => 'foo',
                'order' => 1,
                'path' => null,
            ),
            1 => 
            (object) array(
                'questionId' => 'crop_production',
                'xformQuestionId' => 'crop_production',
                'dataType' => 'begin_repeat',
                'order' => 2,
                'path' => null,
            ),
            2 => 
            (object) array(
                'questionId' => 'crop_production__1__crops',
                'xformQuestionId' => '1__crops',
                'dataType' => 'foo',
                'order' => 3,
                'path' => 'crop_production',
            ),
            3 => 
            (object) array(
                'questionId' => 'crop_production__1__field_size',
                'xformQuestionId' => '1__field_size',
                'dataType' => 'foo',
                'order' => 4,
                'path' => 'crop_production',
            ),
            4 => 
            (object) array(
                'questionId' => 'crop_production__2__crops',
                'xformQuestionId' => '2__crops',
                'dataType' => 'foo',
                'order' => 5,
                'path' => 'crop_production',
            ),
            5 => 
            (object) array(
                'questionId' => 'crop_production__2__field_size',
                'xformQuestionId' => '2__field_size',
                'dataType' => 'foo',
                'order' => 6,
                'path' => 'crop_production',
            ),
            6 => 
            (object) array(
                'questionId' => 'crop_production__3__crops',
                'xformQuestionId' => '3__crops',
                'dataType' => 'foo',
                'order' => 7,
                'path' => 'crop_production',
            ),
            7 => 
            (object) array(
                'questionId' => 'crop_production__3__field_size',
                'xformQuestionId' => '3__field_size',
                'dataType' => 'foo',
                'order' => 8,
                'path' => 'crop_production',
            ),
            8 => 
            (object) array(
                'questionId' => 'school',
                'xformQuestionId' => 'school',
                'dataType' => 'foo',
                'order' => 9,
                'path' => null,
            ),
            9 => 
            (object) array(
                'questionId' => 'school2',
                'xformQuestionId' => 'school2',
                'dataType' => 'begin_group',
                'order' => 10,
                'path' => null,
            ),
            10 => 
            (object) array(
                'questionId' => 'school2__school_sub_q',
                'xformQuestionId' => 'school_sub_q',
                'dataType' => 'begin_group',
                'order' => 11,
                'path' => 'school2',
            ),
            11 => 
            (object) array(
                'questionId' => 'school2__school_sub_q__school_district',
                'xformQuestionId' => 'school_district',
                'dataType' => 'foo',
                'order' => 12,
                'path' => 'school2/school_sub_q',
            ),
            12 => 
            (object) array(
                'questionId' => 'school2__school_sub_q__school_sub_sub_q',
                'xformQuestionId' => 'school_sub_sub_q',
                'dataType' => 'begin_group',
                'order' => 13,
                'path' => 'school2/school_sub_q',
            ),
            13 => 
            (object) array(
                'questionId' => 'school2__school_sub_q__school_sub_sub_q_final',
                'xformQuestionId' => 'final',
                'dataType' => 'foo',
                'order' => 14,
                'path' => 'school2/school_sub_q/school_sub_sub_q',
            ),
        );
        $results = DwProject::reorderQuestionsForBeginRepeat($questions);
        $this->assertEquals($results, $expectedResult);
    }
}
