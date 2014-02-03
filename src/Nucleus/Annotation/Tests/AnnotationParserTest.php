<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nucleus\Annotation\Tests;

use Nucleus\Annotation\AnnotationParser;
use Nucleus\Annotation\NoParsingResultException;

/**
 * Description of AnnotationParserTest
 *
 * @author Martin
 */
class AnnotationParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Nucleus\Annotation\IAnnotationParser
     */
    protected function getAnnotationParserService($configuration)
    {
        return new AnnotationParser($configuration);
    }
    private $annotationParserService;

    /**
     * @return \Nucleus\Annotation\IAnnotationParser
     */
    private function loadAnnotationParserService()
    {
        if (is_null($this->annotationParserService)) {
            $this->annotationParserService = $this->getAnnotationParserService(array('namespaces' => array(__NAMESPACE__)));
            $this->assertInstanceOf('\Nucleus\Annotation\IAnnotationParser', $this->annotationParserService);
        }

        return $this->annotationParserService;
    }

    public function providerTestParseMethod()
    {
        $annotationClass = __NAMESPACE__ . '\TestAnnotation';
        $expectedAnnotations[] = $annotationClass;

        return array(
            //Parent Class
            array(__NAMESPACE__ . '\TestAnnotatedParent', $expectedAnnotations, 'annotated', $expectedAnnotations),
            array(__NAMESPACE__ . '\TestAnnotatedParent', $expectedAnnotations, 'notAnnotated', array()),
            array(__NAMESPACE__ . '\TestAnnotatedParent', $expectedAnnotations, 'annotatedByOverride', array()),
            //Child Class
            array(__NAMESPACE__ . '\TestAnnotatedChild', $expectedAnnotations, 'annotated', $expectedAnnotations),
            array(__NAMESPACE__ . '\TestAnnotatedChild', $expectedAnnotations, 'notAnnotated', array()),
            array(__NAMESPACE__ . '\TestAnnotatedChild', $expectedAnnotations, 'annotatedByOverride', $expectedAnnotations),
            array(__NAMESPACE__ . '\TestAnnotatedChild', $expectedAnnotations, 'doubleAnnotated', array_fill(0, 2, $annotationClass)),
        );
    }

    /**
     * @dataProvider providerTestParseMethod
     * @param string $class
     * @param string $expectedClassAnnotations
     * @param string $method
     * @param string[] $expectedMethodAnnotations
     */
    public function testParseMethod($class, $expectedClassAnnotations, $method, $expectedMethodAnnotations)
    {
        $service = $this->loadAnnotationParserService();

        $result = $service->parse($class);

        $this->assertTrue($result->hasAnnotations());

        $this->assertInstanceOf('Nucleus\Annotation\IParsingResult', $result);

        $this->assertEquals($result->getParsedClassName(), $class);

        $classAnnotations = $result->getClassAnnotations();
        $this->validateAnnotation($expectedClassAnnotations, $classAnnotations);

        $methodAnnotations = $result->getMethodAnnotations($method);
        $this->validateAnnotation($expectedMethodAnnotations, $methodAnnotations);
    }

    public function testParseClassImplement()
    {
        $service = $this->loadAnnotationParserService();

        $result = $service->parse(__NAMESPACE__ . '\TestAnnotatedClassImplement');
        $this->assertInstanceOf('Nucleus\Annotation\IParsingResult', $result);

        $classAnnotations = $result->getClassAnnotations();

        $this->assertEquals(2, count($classAnnotations));

        usort($classAnnotations, function($a, $b) {
            return strcmp(get_class($a), get_class($b));
        });

        $this->assertInstanceOf(__NAMESPACE__ . '\TestAnnotation', $classAnnotations[0]);
        $this->assertInstanceOf(__NAMESPACE__ . '\TestInterfaceAnnotation', $classAnnotations[1]);
    }

    private function validateAnnotation($expectedClassAnnotations, $resultAnnotations)
    {
        $this->assertSameSize($expectedClassAnnotations, $resultAnnotations);
        foreach ($resultAnnotations as $index => $annotation) {
            $this->assertInstanceOf($expectedClassAnnotations[$index], $annotation);
        }
    }

    public function testParseProperty()
    {
        $service = $this->loadAnnotationParserService();

        $result = $service->parse(__NAMESPACE__ . '\TestAnnotatedProperty');
        $this->assertInstanceOf('Nucleus\Annotation\IParsingResult', $result);

        $propertyAnnotations = $result->getAllPropertyAnnotations();

        $this->assertArrayHasKey('property', $propertyAnnotations);
        $this->assertCount(1, $propertyAnnotations['property']);

        $this->assertInstanceOf(__NAMESPACE__ . '\TestAnnotation', $propertyAnnotations['property'][0]);

        $propertyAnnotation = $result->getPropertyAnnotations('property');
        $this->assertCount(1, $propertyAnnotation);
        $this->assertInstanceOf(__NAMESPACE__ . '\TestAnnotation', $propertyAnnotation[0]);
    }

    public function testNoParsingResultException()
    {
        $service = $this->loadAnnotationParserService();
        $result = $service->parse(__NAMESPACE__ . '\TestAnnotation');

        try {
            $result->getMethodAnnotations('doesNotExists');
            $this->fail('Getting annotations of a not existing method should throw a exception');
        } catch (NoParsingResultException $e) {
            $this->assertTrue(true);
        }

        try {
            $result->getPropertyAnnotations('doesNotExists');
            $this->fail('Getting annotations of a not existing property should throw a exception');
        } catch (NoParsingResultException $e) {
            $this->assertTrue(true);
        }
    }
}
if (!class_exists(__NAMESPACE__ . '\TestAnnotation')) {

    /**
     * @Annotation
     */
    class TestAnnotation
    {

    }

    /**
     * @Annotation
     */
    class TestInterfaceAnnotation
    {

    }

    /**
     * @TestAnnotation
     */
    class TestAnnotatedParent
    {

        public function notAnnotated()
        {

        }

        /**
         * @TestAnnotation
         */
        public function annotated()
        {

        }

        public function annotatedByOverride()
        {

        }
    }

    class TestAnnotatedChild extends TestAnnotatedParent
    {

        /**
         * @TestAnnotation
         */
        public function annotatedByOverride()
        {

        }

        /**
         * @TestAnnotation
         * @TestAnnotation
         */
        public function doubleAnnotated()
        {

        }
    }

    /**
     * @TestInterfaceAnnotation
     */
    interface TestAnnotatedInterface
    {

    }

    /**
     * @TestAnnotation
     */
    class TestAnnotatedClassImplement implements TestAnnotatedInterface
    {

    }

    class TestAnnotatedProperty
    {
        /**
         * @TestAnnotation
         * @var type
         */
        public $property;

    }
}
