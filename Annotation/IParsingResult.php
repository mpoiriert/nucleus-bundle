<?php

namespace Nucleus\Bundle\CoreBundle\Annotation;

/**
 * Description of IParsingResult
 *
 * @author Martin
 */
interface IParsingResult
{

    public function getParsedClassName();

    public function getClassAnnotations(array $filters = array());

    public function getAllMethodAnnotations(array $filters = array());

    public function getMethodAnnotations($name, array $filters = array());

    public function getAllPropertyAnnotations(array $filters = array());

    public function getPropertyAnnotations($name, array $filters = array());

    public function hasAnnotations();

    public function getAllAnnotations(array $filters = array());
}
