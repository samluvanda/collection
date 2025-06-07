# Collection Class – Methods Reference

This document contains a comprehensive list of all available methods in the `Collection` class, including usage examples.

---

## 🧱 Creating a Collection

### Description
You can create a new instance of the `Collection` class by passing an array of items to the constructor.

### Parameters
- `array $items` – Optional. The initial array of items (default is an empty array).

### Return
Returns a new `Collection` instance.

### Example
```php
use Collection\Collection;

$collection = new Collection(['apple', 'banana', 'cherry']);
```

---

## Method: `all()`

### Description
Get all items in the collection as a native PHP array.

### Return
- `array`: The underlying array of items.

### Example
```php
$items = $collection->all();
```

---

## Method: `count()`

### Description
Get the number of items in the collection.

### Return
- `int`: Total number of items.

### Example
```php
$total = $collection->count();
```

---

## Method: `isEmpty()`

### Description
Check whether the collection is empty.

### Return
- `bool`: `true` if the collection has no items; otherwise `false`.

### Example
```php
$isEmpty = $collection->isEmpty();
```

---

## Method: `first()`

### Description
Get the first item in the collection.

### Return
- `mixed|null`: The first item or `null` if the collection is empty.

### Example
```php
$first = $collection->first();
```

---

## Method: `last()`

### Description
Get the last item in the collection.

### Return
- `mixed|null`: The last item or `null` if the collection is empty.

### Example
```php
$last = $collection->last();
```



---

## Method: `get($key, $default = null)`

### Description
Retrieve an item from the collection using a key or dot-notated path.

### Parameters
- `string|int $key` – The key or nested path (e.g., 'user.profile.name').
- `mixed $default` – Default value to return if key is not found.

### Return
- `mixed`: The value if found, otherwise the default.

### Example
```php
$name = $collection->get('user.profile.name', 'Guest');
```

---

## Method: `has($key)`

### Description
Determine if the given key or path exists in the collection.

### Parameters
- `string|int $key` – The key or dot-notated path.

### Return
- `bool`: True if the key exists; false otherwise.

### Example
```php
$hasName = $collection->has('user.profile.name');
```

---

## Method: `set($key, $value)`

### Description
Set a value in the collection using dot notation for nested keys.

### Parameters
- `string|int $key` – The key or nested path.
- `mixed $value` – The value to set.

### Return
- `Collection`: The current collection instance.

### Example
```php
$collection->set('user.profile.age', 30);
```

---

## Method: `push($item)`

### Description
Append an item to the end of the collection.

### Parameters
- `mixed $item` – The item to add.

### Return
- `Collection`: The current collection instance.

### Example
```php
$collection->push('new item');
```

---

## Method: `prepend($item)`

### Description
Prepend an item to the beginning of the collection.

### Parameters
- `mixed $item` – The item to add.

### Return
- `Collection`: The current collection instance.

### Example
```php
$collection->prepend('first item');
```

---

## Method: `pop()`

### Description
Remove and return the last item from the collection.

### Return
- `mixed|null`: The removed item or null if the collection is empty.

### Example
```php
$last = $collection->pop();
```

---

## Method: `shift()`

### Description
Remove and return the first item from the collection.

### Return
- `mixed|null`: The removed item or null if the collection is empty.

### Example
```php
$first = $collection->shift();
```

---

## Method: `clear()`

### Description
Remove all items from the collection.

### Return
- `Collection`: The empty collection instance.

### Example
```php
$collection->clear();
```

---

## Method: `implode($glue)`

### Description
Join all items into a string, flattening nested arrays.

### Parameters
- `string $glue` – The separator used between elements.

### Return
- `string`: The resulting string.

### Example
```php
$joined = $collection->implode(', ');
```



---

## Method: `toArray()`

### Description
Convert the collection into a native PHP array.

### Return
- `array`: The internal array of items.

### Example
```php
$array = $collection->toArray();
```

---

## Method: `toJson()`

### Description
Convert the collection into a JSON-encoded string.

### Return
- `string`: JSON representation of the collection.

### Example
```php
$json = $collection->toJson();
```

---

## Method: `chunk($size)`

### Description
Split the collection into smaller sub-collections of the specified size.

### Parameters
- `int $size` – Number of items per chunk.

### Return
- `Collection`: A collection of chunked collections.

### Example
```php
$chunks = $collection->chunk(3);
```

---

## Method: `zip(array $array)`

### Description
Combine each item with the item at the same index from another array.

### Parameters
- `array $array` – The array to zip with.

### Return
- `Collection`: A collection of pairs `[item, array[index]]`.

### Example
```php
$zipped = $collection->zip(['a', 'b', 'c']);
```

---

## Method: `merge(array|Collection $items)`

### Description
Merge another array or collection into the current collection.

### Parameters
- `array|Collection $items` – Items to merge in.

### Return
- `Collection`: A new merged collection.

### Example
```php
$merged = $collection->merge([4, 5, 6]);
```

---

## Method: `tap(callable $callback)`

### Description
Execute a callback with the collection, useful for chaining without affecting the pipeline.

### Parameters
- `callable $callback` – Callback receives the collection.

### Return
- `Collection`: The original collection.

### Example
```php
$collection->tap(function ($col) {
    logger()->info('Tapped', $col->toArray());
});
```



---

## Method: `map(callable $callback)`

### Description
Transform each item in the collection using the given callback.

### Parameters
- `callable $callback` – Function to apply on each item. Receives `($item, $key)`.

### Return
- `Collection`: A new collection with mapped values.

### Example
```php
$mapped = $collection->map(fn($item) => $item * 2);
```

---

## Method: `filter(callable $callback)`

### Description
Keep only items that pass the given test.

### Parameters
- `callable $callback` – Function to test items. Receives `($item, $key)`.

### Return
- `Collection`: A new collection with filtered values.

### Example
```php
$filtered = $collection->filter(fn($item) => $item > 10);
```

---

## Method: `reject(callable $callback)`

### Description
Remove items that pass the given condition (opposite of `filter`).

### Parameters
- `callable $callback` – Function to test items. Receives `($item, $key)`.

### Return
- `Collection`: A new collection with rejected values.

### Example
```php
$rejected = $collection->reject(fn($item) => $item > 10);
```

---

## Method: `reduce(callable $callback, $initial)`

### Description
Reduce the collection to a single value.

### Parameters
- `callable $callback` – Reducing function. Receives `($carry, $item, $key)`.
- `mixed $initial` – Initial accumulator value.

### Return
- `mixed`: The accumulated result.

### Example
```php
$sum = $collection->reduce(fn($carry, $item) => $carry + $item, 0);
```



---

## Method: `pluck($key)`

### Description
Extract values from a specific key or path in each item (supports dot notation).

### Parameters
- `string|int $key` – The key or nested path to extract.

### Return
- `Collection`: A new collection of the extracted values.

### Example
```php
$names = $collection->pluck('user.name');
```

---

## Method: `flatten($depth = INF)`

### Description
Flattens nested arrays into a single-level collection.

### Parameters
- `int|float $depth` – Depth to flatten. Default is `INF` for full flattening.

### Return
- `Collection`: A flattened collection.

### Example
```php
$flat = $collection->flatten();
```

---

## Method: `unique()`

### Description
Remove duplicate values from the collection.

### Parameters
- None

### Return
- `Collection`: A collection of unique values.

### Example
```php
$unique = $collection->unique();
```

---

## Method: `reverse()`

### Description
Reverse the order of items in the collection.

### Parameters
- None

### Return
- `Collection`: A reversed collection.

### Example
```php
$reversed = $collection->reverse();
```

---

## Method: `sort(callable $callback = null)`

### Description
Sort items in the collection optionally using a custom comparator.

### Parameters
- `callable|null $callback` – Optional comparison function for sorting.

### Return
- `Collection`: A sorted collection.

### Example
```php
$sorted = $collection->sort();
```



---

## Method: `sortBy($key)`

### Description
Sorts the collection by the value of a given key (supports dot notation).

### Parameters
- `string|int $key` – The key or nested path to sort by.

### Return
- `Collection`: A sorted collection.

### Example
```php
$sorted = $collection->sortBy('user.age');
```

---

## Method: `groupBy($key)`

### Description
Groups the collection's items by a given key or field.

### Parameters
- `string|int $key` – The key or nested path to group by.

### Return
- `Collection`: A collection where keys are the group values and values are arrays of items.

### Example
```php
$grouped = $collection->groupBy('department');
```

---

## Method: `contains($valueOrCallback)`

### Description
Checks if any item in the collection matches the given value or passes the callback condition.

### Parameters
- `mixed $valueOrCallback` – A value to search for or a callback function.

### Return
- `bool`: `true` if a match is found, `false` otherwise.

### Example
```php
$hasAdmin = $collection->contains(fn($user) => $user['role'] === 'admin');
```

---

## Method: `every(callable $callback)`

### Description
Determines if all items pass the given test.

### Parameters
- `callable $callback` – A function that returns `true` or `false`.

### Return
- `bool`: `true` if all pass, `false` otherwise.

### Example
```php
$allValid = $collection->every(fn($item) => isset($item['id']));
```

---

## Method: `some(callable $callback)`

### Description
Determines if at least one item passes the given test.

### Parameters
- `callable $callback` – A function that returns `true` or `false`.

### Return
- `bool`: `true` if any pass, `false` otherwise.

### Example
```php
$hasErrors = $collection->some(fn($item) => $item['status'] === 'error');
```



---

## Method: `find(callable $callback)`

### Description
Finds and returns the first item that satisfies the given callback condition.

### Parameters
- `callable $callback` – A function that receives `$item, $key` and returns `true` if the item matches.

### Return
- `mixed|null`: The first matching item or `null` if none found.

### Example
```php
$match = $collection->find(fn($user) => $user['active'] === true);
```

---

## Method: `search($value, bool $strict = true)`

### Description
Searches for a value and returns its key using optional strict comparison.

### Parameters
- `mixed $value` – The value to search for.
- `bool $strict` – Whether to use strict comparison (`===`).

### Return
- `int|string|null`: The key if found, otherwise `null`.

### Example
```php
$key = $collection->search('apple');
```

---

## Method: `indexOf($value, bool $strict = true)`

### Description
Returns the numeric index of a value in the collection.

### Parameters
- `mixed $value` – The value to locate.
- `bool $strict` – Whether to use strict comparison (`===`).

### Return
- `int|null`: The index or `null` if not found.

### Example
```php
$index = $collection->indexOf('banana');
```



---

## Method: `countBy(callable|string $callback)`

### Description
Counts the items in the collection grouped by the result of a callback or the value of a given key.

### Parameters
- `callable|string $callback` – A callback or dot-notated key used to group and count.

### Return
- `Collection`: A new collection with grouped counts.

### Example
```php
$counts = $collection->countBy('role'); // ['admin' => 3, 'user' => 5]
```

---

## Method: `sum(callable|string|null $callback = null)`

### Description
Calculates the total sum of all items or values retrieved via a callback/key.

### Parameters
- `callable|string|null $callback` – Optional callback or key to extract values.

### Return
- `float|int`: The total sum.

### Example
```php
$total = $collection->sum('amount');
```

---

## Method: `avg(callable|string|null $callback = null)`

### Description
Calculates the average of values in the collection.

### Parameters
- `callable|string|null $callback` – Optional callback or key to extract values.

### Return
- `float|int|null`: Average value or `null` if the collection is empty.

### Example
```php
$average = $collection->avg('rating');
```



---

## Method: `partition(callable $callback)`

### Description
Splits the collection into two collections: items that pass the callback test and items that don't.

### Parameters
- `callable $callback` – A test function receiving `$item, $key`.

### Return
- `Collection`: A collection containing two sub-collections: `[0 => pass, 1 => fail]`.

### Example
```php
[$active, $inactive] = $collection->partition(fn($user) => $user['active']);
```

---

## Method: `min(callable|string|null $callback = null)`

### Description
Finds the minimum value in the collection.

### Parameters
- `callable|string|null $callback` – Optional callback or key for value extraction.

### Return
- `mixed|null`: Minimum value or `null` if the collection is empty.

### Example
```php
$min = $collection->min('price');
```

---

## Method: `max(callable|string|null $callback = null)`

### Description
Finds the maximum value in the collection.

### Parameters
- `callable|string|null $callback` – Optional callback or key for value extraction.

### Return
- `mixed|null`: Maximum value or `null` if the collection is empty.

### Example
```php
$max = $collection->max('price');
```



---

## Method: `nth(int $step, int $offset = 0)`

### Description
Returns every nth item in the collection, starting from an optional offset.

### Parameters
- `int $step` – Interval between items to include.
- `int $offset` – Index to start from (default: 0).

### Return
- `Collection`: A new collection of selected items.

### Example
```php
$sampled = $collection->nth(2); // Every second item
```

---

## Method: `shuffle()`

### Description
Randomly shuffles the items in the collection.

### Parameters
- None

### Return
- `Collection`: A new collection with items in random order.

### Example
```php
$shuffled = $collection->shuffle();
```

