**[Back](../Readme.md)**

# Write
```php
	$collection = GeneralCollection::create();
	$entity = $collection->createEntity(); // Insert entity to index +1
	$entity->id = 1;
	$entity->name = 'First';

	$entity = $collection[1] = GeneralEntity::create(); // Insert entity to index 1
	$entity->id = 2;
	$entity->name = 'Second';

	$collection[3] = GeneralEntity::create();  // Insert entity to index 2
	unset($collection[3]); // Remove entity with index 2

	echo json_encode($collection[1]); // Print '{"CUSTOM_ID":1,"name":"First"}'

	echo $collection[1]; // Print '{"CUSTOM_ID":1,"name":"First"}'

	echo json_encode($collection); // Print '[{"CUSTOM_ID":1,"name":"First"},{"CUSTOM_ID":2,"name":"Second"}]'

	echo $collection; // Print '[{"CUSTOM_ID":1,"name":"First"},{"CUSTOM_ID":2,"name":"Second"}]'
```