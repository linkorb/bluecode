<?php

use BlueCode\Repository\FileProjectRepository;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Model/Project.php';
require_once __DIR__ . '/../src/Model/Concept.php';
require_once __DIR__ . '/../src/Model/Route.php';
require_once __DIR__ . '/../src/Model/Table.php';
require_once __DIR__ . '/../src/Model/Column.php';
require_once __DIR__ . '/../src/Repository/FileProjectRepository.php';

final class FileProjectRepositoryLoadProjectTest extends TestCase
{
    private string $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = sys_get_temp_dir() . '/bluecode-project-' . bin2hex(random_bytes(8));
        mkdir($this->projectDir);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->projectDir . '/*') as $file) {
            unlink($file);
        }
        rmdir($this->projectDir);
    }

    public function testMissingProjectFileReportsItsPath(): void
    {
        $repository = new FileProjectRepository(['blog' => $this->projectDir]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found: ' . $this->projectDir . '/bluecode.yml');
        $repository->getByCode('blog');
    }

    public function testProjectIsBuiltFromConfiguredFiles(): void
    {
        file_put_contents($this->projectDir . '/bluecode.yml', <<<'YAML'
name: Blog
summary: Blog summary
description: Blog description
concepts:
    post: A published article
routes: custom-routes.yml
schema: custom-schema.xml
YAML
        );
        file_put_contents($this->projectDir . '/custom-routes.yml', <<<'YAML'
index:
    path: /posts
    defaults: { _controller: PostController::index }
    options:
        bluecode:
            summary: List posts
            description: Shows posts
view:
    path: /posts/{id}
    defaults: { _controller: PostController::view }
    options:
        bluecode: { }
YAML
        );
        file_put_contents($this->projectDir . '/custom-schema.xml', <<<'XML'
<schema>
    <table name="post" description="Blog posts">
        <column name="id" type="integer" length="11" comment="Identifier" autoincrement="true" unsigned="true" />
        <column name="title" type="string" length="100" />
    </table>
</schema>
XML
        );

        $project = (new FileProjectRepository(['blog' => $this->projectDir]))->getByCode('blog');

        self::assertSame('blog', $project->getCode());
        self::assertSame('Blog', $project->getName());
        self::assertSame('Blog summary', $project->getSummary());
        self::assertSame('Blog description', $project->getDescription());
        self::assertSame('A published article', $project->getConcept('post')->getDescription());
        self::assertCount(2, $project->getRoutes());
        $index = $project->getRoute('index');
        self::assertSame('/posts', $index->getPath());
        self::assertSame('PostController::index', $index->getController());
        self::assertSame('List posts', $index->getSummary());
        self::assertSame('Shows posts', $index->getDescription());
        $view = $project->getRoute('view');
        self::assertSame('/posts/{id}', $view->getPath());
        self::assertNull($view->getSummary());
        self::assertNull($view->getDescription());
        self::assertCount(1, $project->getTables());
        $table = $project->getTable('post');
        self::assertSame('Blog posts', $table->getDescription());
        self::assertCount(2, $table->getColumns());
        $id = $table->getColumns()['id'];
        self::assertSame('integer', $id->getType());
        self::assertSame('11', $id->getLength());
        self::assertSame('Identifier', $id->getComment());
        self::assertSame('true', $id->getAutoIncrement());
        self::assertSame('true', $id->getUnsigned());
        self::assertSame('string', $table->getColumns()['title']->getType());
    }
}
