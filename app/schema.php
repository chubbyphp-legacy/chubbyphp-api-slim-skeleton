<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Doctrine\DBAL\Schema\Schema;

$schema = new Schema();

$courses = $schema->createTable('courses');
$courses->addColumn('id', 'guid');
$courses->addColumn('createdAt', 'datetime');
$courses->addColumn('updatedAt', 'datetime', ['notnull' => false]);
$courses->addColumn('name', 'string');
$courses->addColumn('level', 'integer');
$courses->addColumn('progress', 'decimal', ['precision' => 3, 'scale' => 2]);
$courses->addColumn('active', 'boolean');
$courses->setPrimaryKey(['id']);
$courses->addUniqueIndex(['name']);

$documents = $schema->createTable('documents');
$documents->addColumn('id', 'guid');
$documents->addColumn('courseId', 'guid');
$documents->addColumn('createdAt', 'datetime');
$documents->addColumn('updatedAt', 'datetime', ['notnull' => false]);
$documents->addColumn('name', 'string');
$documents->addColumn('url', 'string');
$documents->setPrimaryKey(['id']);
$documents->addForeignKeyConstraint($courses, ['courseId'], ['id'], ['onDelete' => 'CASCADE']);
$documents->addUniqueIndex(['courseId', 'name']);

return $schema;
