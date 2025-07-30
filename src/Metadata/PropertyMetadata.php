<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Metadata;

use Matraux\JsonORM\Json\JsonProperty;
use Nette\Utils\Type;
use ReflectionAttribute;
use ReflectionProperty;

final readonly class PropertyMetadata
{

	public string $name;

	public string $index;

	public ?string $type;

	protected function __construct(protected readonly ReflectionProperty $reflection)
	{
		$this->name = $this->reflection->name;

		$attributes = $this->reflection->getAttributes(JsonProperty::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->index = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$type = Type::fromReflection($this->reflection);
		$this->type = $type?->isClass() ? $type->getSingleName() : null;
	}

	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

}
