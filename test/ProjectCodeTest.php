<?php

use BlueCode\Model\Project;
use PHPUnit\Framework\TestCase;

final class ProjectCodeTest extends TestCase
{
    public function testCodeCanBeSetAndRead(): void
    {
        $project = new Project();
        $project->setCode('example');

        self::assertSame('example', $project->getCode());
    }
}
