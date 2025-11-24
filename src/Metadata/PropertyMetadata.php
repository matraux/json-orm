<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Metadata;

use Matraux\JsonOrm\Codec\Codec;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\JsonOrm\Json\Property;
use Nette\Utils\Type;
use ReflectionAttribute;
use ReflectionProperty;

final readonly class PropertyMetadata
{

	public string $name;

	public string $index;

	public ?string $type;

	public ?Codec $codec;

	protected function __construct(protected readonly ReflectionProperty $reflection)
	{
		$this->name = $this->reflection->name;

		$attributes = $this->reflection->getAttributes(Property::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->index = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$type = Type::fromReflection($this->reflection);
		$this->type = $type?->isClass() ? $type->getSingleName() : null;

		$attributes = $this->reflection->getAttributes(Codec::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new CodecException(sprintf('Property %s::$%s expects single %s attribute, multiple given.', $this->reflection->getDeclaringClass()->getName(), $this->name, Codec::class));
		}

		$this->codec = array_shift($attributes)?->newInstance();
	}

	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

}
