<?php

namespace Support;

class Collection
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get all items in the collection as an array.
     *
     * @return array The underlying array of items.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the number of items in the collection.
     *
     * @return int Total number of items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Check if the collection is empty.
     *
     * @return bool True if no items exist; false otherwise.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Get the first item in the collection.
     *
     * @return mixed|null The first item, or null if the collection is empty.
     */
    public function first()
    {
        return $this->items[0] ?? null;
    }

    /**
     * Get the last item in the collection.
     *
     * @return mixed|null The last item, or null if the collection is empty.
     */
    public function last()
    {
        return !empty($this->items) ? $this->items[array_key_last($this->items)] : null;
    }

    /**
     * Get an item from the collection using a key, supporting dot notation for nested access.
     *
     * Example:
     * $collection->get('user.profile.name', 'Default');
     *
     * @param string|int $key The key or nested path (e.g., 'user.profile.name')
     * @param mixed $default The default value to return if the key is not found
     * @return mixed The value if found, otherwise the default
     */
    public function get($key, $default = null)
    {
        return Arr::getNested($this->items, $key, $default);
    }

    /**
     * Determine if the given key exists in the collection.
     *
     * @param string|int $key The key or dot-notated path to check.
     * @return bool True if the key exists, false otherwise.
     */
    public function has($key): bool
    {
        return \Support\Arr::hasNested($this->items, $key);
    }

    /**
     * Set a value in the collection, supporting dot notation for nested paths.
     *
     * @param string|int $key The key or nested path (e.g., 'user.profile.name')
     * @param mixed $value The value to set.
     * @return static
     */
    public function set($key, $value): static
    {
        \Support\Arr::setNested($this->items, $key, $value);
        return $this;
    }

    /**
     * Add an item to the end of the collection.
     *
     * @param mixed $item The item to append.
     * @return static
     */
    public function push($item): static
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Add an item to the beginning of the collection.
     *
     * @param mixed $item The item to prepend.
     * @return static
     */
    public function prepend($item): static
    {
        array_unshift($this->items, $item);
        return $this;
    }

    /**
     * Remove and return the last item from the collection.
     *
     * @return mixed|null The removed item, or null if the collection is empty.
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Remove and return the first item from the collection.
     *
     * @return mixed|null The removed item, or null if the collection is empty.
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Remove all items from the collection.
     *
     * @return static
     */
    public function clear(): static
    {
        $this->items = [];
        return $this;
    }

    /**
     * Join all items into a string using a separator.
     * Automatically flattens nested arrays.
     *
     * @param string $glue Separator used between elements.
     * @return string Imploded string.
     */
    public function implode(string $glue): string
    {
        $flatten = function (array $items) use (&$flatten): array {
            $result = [];
            foreach ($items as $item) {
                if (is_array($item)) {
                    $result = array_merge($result, $flatten($item));
                } else {
                    $result[] = $item;
                }
            }
            return $result;
        };

        return implode($glue, $flatten($this->items));
    }

    /**
     * Get the underlying items as a native PHP array.
     *
     * @return array The internal array of items.
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Convert the collection to a JSON-encoded string.
     *
     * @return string JSON representation of the collection.
     */
    public function toJson(): string
    {
        return json_encode($this->items);
    }

    /**
     * Split the collection into smaller chunks.
     *
     * @param int $size The size of each chunk.
     * @return Collection A collection of chunked sub-collections.
     */
    public function chunk(int $size): static
    {
        $chunks = array_chunk($this->items, $size);

        return new static(array_map(fn($chunk) => new static($chunk), $chunks));
    }

    /**
     * Combine the collection with another array by index.
     *
     * @param array $array The array to zip with.
     * @return static A collection of [item, other] pairs.
     */
    public function zip(array $array): static
    {
        $zipped = [];

        foreach (array_values($this->items) as $i => $value) {
            $zipped[] = [$value, $array[$i] ?? null];
        }

        return new static($zipped);
    }

    /**
     * Merge another array or collection into the current collection.
     *
     * @param array|Collection $items Items to merge.
     * @return static
     */
    public function merge(array|Collection $items): static
    {
        $mergeWith = $items instanceof self ? $items->all() : $items;

        return new static(array_merge($this->items, $mergeWith));
    }

    /**
     * Call the given callback with the collection and return the collection.
     *
     * @param callable $callback A function to receive the collection instance.
     * @return static
     */
    public function tap(callable $callback): static
    {
        $callback($this);
        return $this;
    }

    /**
     * Apply a callback to each item in the collection.
     *
     * @param callable $callback The callback receives ($item, $key).
     * @return static A new collection with transformed items.
     */
    public function map(callable $callback): static
    {
        $mapped = [];

        foreach ($this->items as $key => $item) {
            $mapped[$key] = $callback($item, $key);
        }

        return new static($mapped);
    }

    /**
     * Filter items using a callback.
     *
     * @param callable $callback The callback receives ($item, $key).
     * @return static A new collection with only items that pass the condition.
     */
    public function filter(callable $callback): static
    {
        $filtered = [];

        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                $filtered[$key] = $item;
            }
        }

        return new static($filtered);
    }

    /**
     * Reject items using a callback (inverse of filter).
     *
     * @param callable $callback The callback receives ($item, $key).
     * @return static A new collection with items that fail the condition.
     */
    public function reject(callable $callback): static
    {
        return $this->filter(fn($item, $key) => !$callback($item, $key));
    }

    /**
     * Reduce the collection to a single value using a callback.
     *
     * @param callable $callback The callback receives ($carry, $item, $key).
     * @param mixed $initial Initial value for accumulation.
     * @return mixed The final accumulated value.
     */
    public function reduce(callable $callback, $initial)
    {
        $result = $initial;

        foreach ($this->items as $key => $item) {
            $result = $callback($result, $item, $key);
        }

        return $result;
    }

    /**
     * Extract values of a given key from each item (array or object), supporting dot notation.
     *
     * @param string|int $key The key or nested path to pluck (e.g., 'user.profile.name').
     * @return static A collection of extracted values.
     */
    public function pluck($key): static
    {
        $plucked = [];

        foreach ($this->items as $item) {
            if (is_array($item)) {
                $value = Arr::getNested($item, $key);
            } elseif (is_object($item)) {
                $value = Arr::getNested((array)$item, $key); // cast object to array
            } else {
                $value = null;
            }

            $plucked[] = $value;
        }

        return new static($plucked);
    }

    /**
     * Flatten nested arrays to a single-level array.
     *
     * @param int|float $depth The depth to flatten (default: INF for full depth).
     * @return static A new collection of flattened values.
     */
    public function flatten($depth = INF): static
    {
        $flatten = function (array $items, $depth) use (&$flatten): array {
            $result = [];

            foreach ($items as $item) {
                if (!is_array($item)) {
                    $result[] = $item;
                } elseif ($depth === 1) {
                    $result = array_merge($result, $item);
                } else {
                    $result = array_merge($result, $flatten($item, $depth - 1));
                }
            }

            return $result;
        };

        return new static($flatten($this->items, $depth));
    }

    /**
     * Remove duplicate values from the collection.
     *
     * @return static A collection with unique values only.
     */
    public function unique(): static
    {
        return new static(array_values(array_unique($this->items, SORT_REGULAR)));
    }

    /**
     * Reverse the order of the items in the collection.
     *
     * @return static A new collection with reversed order.
     */
    public function reverse(): static
    {
        return new static(array_reverse($this->items, true));
    }

    /**
     * Sort the collection. Optionally use a custom comparator.
     *
     * @param callable|null $callback A function to compare values ($a, $b).
     * @return static A new sorted collection.
     */
    public function sort(?callable $callback = null): static
    {
        $sorted = $this->items;

        if ($callback) {
            uasort($sorted, $callback);
        } else {
            asort($sorted);
        }

        return new static($sorted);
    }

    /**
     * Sort the collection by the given key (supports nested keys and dot notation).
     *
     * @param string|int $key The key or path to sort by.
     * @return static A new sorted collection.
     */
    public function sortBy($key): static
    {
        $items = $this->items;

        uasort($items, function ($a, $b) use ($key) {
            $valA = is_array($a) ? Arr::getNested($a, $key) : (is_object($a) ? Arr::getNested((array) $a, $key) : null);
            $valB = is_array($b) ? Arr::getNested($b, $key) : (is_object($b) ? Arr::getNested((array) $b, $key) : null);
            return $valA <=> $valB;
        });

        return new static($items);
    }

    /**
     * Group the collection's items by a given key or field.
     *
     * @param string|int $key The key to group by (supports dot notation).
     * @return static A new collection where keys are grouped values.
     */
    public function groupBy($key): static
    {
        $grouped = [];

        foreach ($this->items as $item) {
            $value = is_array($item) ? Arr::getNested($item, $key)
                : (is_object($item) ? Arr::getNested((array) $item, $key) : null);

            $grouped[$value][] = $item;
        }

        return new static($grouped);
    }

    /**
     * Determine if the collection contains a given value or passes a callback condition.
     *
     * @param mixed $valueOrCallback A value to compare or a callable ($item, $key) => bool.
     * @return bool True if any item matches, false otherwise.
     */
    public function contains($valueOrCallback): bool
    {
        foreach ($this->items as $key => $item) {
            if (is_callable($valueOrCallback)) {
                if ($valueOrCallback($item, $key)) {
                    return true;
                }
            } else {
                if ($item === $valueOrCallback) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if all items pass the given test.
     *
     * @param callable $callback The callback receives ($item, $key).
     * @return bool True if all pass, false otherwise.
     */
    public function every(callable $callback): bool
    {
        foreach ($this->items as $key => $item) {
            if (!$callback($item, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if at least one item passes the given test.
     *
     * @param callable $callback The callback receives ($item, $key).
     * @return bool True if any pass, false otherwise.
     */
    public function some(callable $callback): bool
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the first item that passes the callback condition.
     *
     * @param callable $callback The callback receives ($item, $key).
     * @return mixed|null The first matching item or null if not found.
     */
    public function find(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Search for a value and return its key using strict comparison.
     *
     * @param mixed $value The value to search for.
     * @param bool $strict Whether to use strict comparison (===).
     * @return int|string|null The key if found, or null.
     */
    public function search($value, bool $strict = true): int|string|null
    {
        $key = array_search($value, $this->items, $strict);
        return $key === false ? null : $key;
    }

    /**
     * Get the numeric index of a value (like search, but forces index result).
     *
     * @param mixed $value The value to search for.
     * @param bool $strict Use strict comparison.
     * @return int|null The index or null if not found.
     */
    public function indexOf($value, bool $strict = true): ?int
    {
        $keys = array_keys($this->items);
        $key = array_search($value, $this->items, $strict);

        return $key === false ? null : array_search($key, $keys);
    }

    /**
     * Count items in the collection grouped by a key or callback result.
     *
     * @param callable|string $callback A callback or dot-notated key to group by.
     * @return static A new collection with keys and their counts.
     */
    public function countBy(callable|string $callback): static
    {
        $counts = [];

        foreach ($this->items as $item) {
            $group = is_callable($callback)
                ? $callback($item)
                : (is_array($item) ? Arr::getNested($item, $callback) : (is_object($item) ? Arr::getNested((array)$item, $callback) : null));

            $counts[$group] = ($counts[$group] ?? 0) + 1;
        }

        return new static($counts);
    }

    /**
     * Sum the values in the collection.
     *
     * @param callable|string|null $callback A key, callback, or null.
     * @return float|int The total sum.
     */
    public function sum(callable|string|null $callback = null): float|int
    {
        $total = 0;

        foreach ($this->items as $item) {
            if (is_callable($callback)) {
                $total += $callback($item);
            } elseif (is_string($callback)) {
                $value = is_array($item)
                    ? Arr::getNested($item, $callback)
                    : (is_object($item) ? Arr::getNested((array)$item, $callback) : null);
                $total += $value;
            } else {
                $total += $item;
            }
        }

        return $total;
    }

    /**
     * Get the average of values in the collection.
     *
     * @param callable|string|null $callback Optional key or callback.
     * @return float|int|null Average value or null if empty.
     */
    public function avg(callable|string|null $callback = null): float|int|null
    {
        if (count($this->items) === 0) {
            return null;
        }

        return $this->sum($callback) / count($this->items);
    }

    /**
     * Partition the collection into two groups based on a truth test.
     *
     * @param callable $callback The test function ($item, $key).
     * @return static A collection with two collections: [0 => pass, 1 => fail]
     */
    public function partition(callable $callback): static
    {
        $pass = [];
        $fail = [];

        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                $pass[$key] = $item;
            } else {
                $fail[$key] = $item;
            }
        }

        return new static([new static($pass), new static($fail)]);
    }

    /**
     * Get the minimum value in the collection.
     *
     * @param callable|string|null $callback A callback or key (optional).
     * @return mixed|null The minimum value or null if empty.
     */
    public function min(callable|string|null $callback = null)
    {
        if (empty($this->items)) return null;

        $values = $this->mapValueResolver($callback);
        return min($values);
    }

    /**
     * Get the maximum value in the collection.
     *
     * @param callable|string|null $callback A callback or key (optional).
     * @return mixed|null The maximum value or null if empty.
     */
    public function max(callable|string|null $callback = null)
    {
        if (empty($this->items)) return null;

        $values = $this->mapValueResolver($callback);
        return max($values);
    }

    /**
     * Return every nth item from the collection, starting at offset.
     *
     * @param int $step Step between values.
     * @param int $offset Starting index.
     * @return static A collection of every nth item.
     */
    public function nth(int $step, int $offset = 0): static
    {
        $result = [];

        $index = 0;
        foreach ($this->items as $key => $value) {
            if ($index >= $offset && ($index - $offset) % $step === 0) {
                $result[$key] = $value;
            }
            $index++;
        }

        return new static($result);
    }

    /**
     * Shuffle the items randomly.
     *
     * @return static A new shuffled collection.
     */
    public function shuffle(): static
    {
        $items = $this->items;
        shuffle($items);

        return new static($items);
    }

    /**
     * Resolve item values via callback or key string for reduction helpers.
     *
     * @param callable|string|null $resolver
     * @return array The resolved values.
     */
    private function mapValueResolver(callable|string|null $resolver = null): array
    {
        $values = [];

        foreach ($this->items as $item) {
            if (is_callable($resolver)) {
                $values[] = $resolver($item);
            } elseif (is_string($resolver)) {
                $values[] = is_array($item)
                    ? \Support\Arr::getNested($item, $resolver)
                    : (is_object($item) ? \Support\Arr::getNested((array)$item, $resolver) : null);
            } else {
                $values[] = $item;
            }
        }

        return $values;
    }
}
