# MATRAUX JSON ORM
[![Latest Version on Packagist](https://img.shields.io/packagist/v/matraux/json-orm.svg?logo=packagist&logoColor=white)](https://packagist.org/packages/matraux/json-orm)
[![Last release](https://img.shields.io/github/v/release/matraux/json-orm?display_name=tag&logo=github&logoColor=white)](https://github.com/matraux/json-orm/releases)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?logo=open-source-initiative&logoColor=white)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg?logo=php&logoColor=white)](https://php.net)
[![Security Policy](https://img.shields.io/badge/Security-Policy-blue?logo=bitwarden&logoColor=white)](./.github/SECURITY.md)
[![Contributing](https://img.shields.io/badge/Contributing-Disabled-lightgrey?logo=github&logoColor=white)](CONTRIBUTING.md)
[![QA Status](https://img.shields.io/github/actions/workflow/status/matraux/json-orm/qa.yml?label=Quality+Assurance&logo=checkmarx&logoColor=white)](https://github.com/matraux/json-orm/actions/workflows/qa.yml)
[![Issues](https://img.shields.io/github/issues/matraux/json-orm?logo=github&logoColor=white)](https://github.com/matraux/json-orm/issues)
[![Last Commit](https://img.shields.io/github/last-commit/matraux/json-orm?logo=git&logoColor=white)](https://github.com/matraux/json-orm/commits)

<br>

## Introduction
A PHP 8.4+ library for converting JSON data to typed entities and back, with support for lazy-loading collections, immutable data structures, and structured entity design.
Useful for structured JSON APIs, configuration parsing, and object-based manipulation of hierarchical JSON data.


<br>

## Features
- Object-oriented JSON mapping
- Conversion from JSON to typed entities and back
- Lazy-loading collections for efficient memory usage
- Entity objects are mutable and can be freely modified after creation
- Strict type support with automatic casting
- Native support for nested structures and arrays
- Easy integration with configuration or API responses

<br>

## Installation
```bash
composer require matraux/json-orm
```

<br>

## Requirements
| version | PHP | Note
|----|---|---
| 1.1.0 | 8.3+ | Initial commit
| 1.2.0 | 8.4+ | Performance optimization
| 1.3.0 | 8.4+ | Performance optimization
| 1.4.1 | 8.4+ | 50%+ performance boost via metadata caching
| 1.4.2 | 8.4+ | Entity & Metadata fix and optimization

<br>

## Examples
See [Definitions](./docs/Definitions.md)  for how to define your own entities and collections.

See [Read](./docs/Read.md) for full reading examples.
```php
use Matraux\JsonORM\Json\JsonReader;

// Load data from JSON string or file
$reader = JsonReader::fromJson('[{"CUSTOM_ID":1,"name":"First"}]');

// Create typed collection from JSON
$collection = CommonCollection::create($reader);

$entity = $collection[0];
echo $entity->name; // "First"
```

See [Write](./docs/Write.md) for writing examples.
```php
// Create collection and insert entity
$collection = CommonCollection::create();

$entity = $collection->createEntity();
$entity->name = 'Example';

echo json_encode($collection);
// '[{"name":"Example"}]'
```

<br>

## Development
See [Development](./docs/Development.md) for debug, test instructions, static analysis, and coding standards.

<br>

## Support
For bug reports and feature requests, please use the [issue tracker](https://github.com/matraux/json-orm/issues).