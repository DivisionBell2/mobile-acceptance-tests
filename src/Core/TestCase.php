<?php

/**
 * @description Продуктовый TestCase, от которого должны наследоваться все тесты проекта
 */

namespace Core;

use AcceptanceCore\Core\AnnotationsNames;
use Core\Utils\Domains;
use src\Annotation\ArrayAnnotation;
use src\AnnotationProcessor;

class TestCase extends \AcceptanceCore\Core\TestCase
{
    protected function setUp(): void
    {
        $this->processProjectAnnotations();
        parent::setUp();
    }

    protected function processProjectAnnotations()
    {
        $class = get_class($this);
        $methodName = $this->getName(false);
        $annotationProcessor = new AnnotationProcessor($class, $methodName);

        // Обработчик @domains аннотации
        if ($domains = $annotationProcessor->process(new ArrayAnnotation(AnnotationsNames::DOMAINS))) {
            if (Domains::isDevelDomain() && !in_array(Domains::DEVEL, $domains)) {
                $this->markTestSkipped("Skip this test that is not for Devel: {$class}::{$methodName}");
            } elseif (Domains::isStageDomain() && !in_array(Domains::STAGE, $domains)) {
                $this->markTestSkipped("Skip this test that is not for Stage: {$class}::{$methodName}");
            } elseif (Domains::isProductionDomain() && !in_array(Domains::PRODUCTION, $domains)) {
                $this->markTestSkipped("Skip this test that is not for Production: {$class}::{$methodName}");
            }
        }
    }
}
