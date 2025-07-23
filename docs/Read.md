**[Back](../Readme.md)**

# Read

## JSON data
```json
[
	{
		"CUSTOM_ID": 1,
		"name": "First"
	},
  {
		"CUSTOM_ID": 2,
		"name": "Second"
	},
	{
		"CUSTOM_ID": 3
	}
]
```

## Reading by collection
```php
	use Matraux\JsonORM\Json\JsonReader;

	$reader = JsonReader::fromFile('path to JSON file'); // Access data from file
	$reader = JsonReader::fromJson('JSON string'); // Access data from string

	$collection = GeneralCollection::create($reader); // Create collection

	/**
	 * ArrayAccess
	 */
	$entity = $collection[0];
	echo $entity->id; // Print 1
	echo $entity->name; // Print "First"

	/**
	 * Iterator
	 */
	foreach($collection as $entity) {
		echo $entity->id; // Print 1, 2 and 3
		echo $entity->name; // Print "First", "Second" and null
	}

	echo count($collection); // Print count of entities
```

## JSON data
```json
{
	"CUSTOM_ID": 1,
	"name": "First"
},
```

## Reading by entity
```php
	use Matraux\JsonORM\Json\JsonReader;

	$reader = JsonReader::fromFile('path to JSON file'); // Access data from file
	$reader = JsonReader::fromJson('JSON string'); // Access data from string

	$entity = GeneralEntity::create($reader);

	echo $entity->id; // Print "1"

	echo $entity->name; // Print "First"
```

