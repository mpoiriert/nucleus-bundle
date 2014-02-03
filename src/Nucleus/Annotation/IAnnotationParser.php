<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nucleus\Annotation;

/**
 *
 * @author Martin
 */
interface IAnnotationParser
{
    /**
     * The service name use as a reference
     */
    const NUCLEUS_SERVICE_NAME = "annotationParser";

    /**
     * 
     * @param type $className
     * 
     * @return \Nucleus\IService\Annotation\IParsingResult
     */
    public function parse($className);
}