**[Back](../README.md)**

# Write
```php
	$collection = CommonCollection::create();
	$entity = $collection->createEntity(); // Insert entity to index 0
	$entity->id = 1;
	$entity->name = 'First';
	$entity->time = new DateTime('now');

	$entity = $collection[0] = CommonEntity::create(); // Insert entity to index 1
	$entity->id = 2;
	$entity->name = 'Second';

	$collection[1] = CommonEntity::create();  // Insert entity to index 2
	unset($collection[1]); // Remove entity with index 2

	echo json_encode($collection[0]); // Print '{"CUSTOM_ID":1,"name":"First","TIME":"24.11.2025 14:35:00.000000"}'

	echo $collection[0]; // Print '{"CUSTOM_ID":1,"name":"First","TIME":"24.11.2025 14:35:00.000000"}'

	echo json_encode($collection); // Print '[{"CUSTOM_ID":1,"name":"First","TIME":"24.11.2025 14:35:00.000000"},{"CUSTOM_ID":2,"name":"Second"}]'

	echo $collection; // Print '[{"CUSTOM_ID":1,"name":"First","TIME":"24.11.2025 14:35:00.000000"},{"CUSTOM_ID":2,"name":"Second"}]'
```