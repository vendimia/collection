<?php
namespace Vendimia\Collection;

use Vendimia\Interface\DataType\Arrayable;
use ArrayAccess;
use Countable;
use Iterator;

/**
 * Collection: Object-oriented array reimplementation
 */
class Collection implements Arrayable, ArrayAccess, Countable, Iterator
{
    protected $storage = [];

    public function __construct(array|Arrayable $array = []) {
        if ($array instanceof Arrayable) {
            $this->storage = $array->asArray();
        } else {
            $this->storage = $array;
        }
    }

    /**
     * Returns true when this collection is a list.
     *
     * A list has sequential positive integer indexes starting with 0
     */
    public function isList(): bool
    {
        return array_is_list($this->storage);
    }

    /**
     * Returns whether an index exists on the collection
     */
    public function has($index): bool
    {
        return key_exists($index, $this->storage);
    }

    /**
     * Returns an element of this collection.
     */
    public function &get($index): mixed
    {
        return $this->storage[$index];
    }

    /**
     * Searches for a value in the collection, returns the index or key, or
     * false if the value doesn't exists.
     */
    public function search($value): int|string|false
    {
        return array_search($this->storage, $value);
    }

    /**
     * Adds or update an element to this collection.
     *
     * If the collection is a list, omitting the offset will append the value.
     */
    public function set($value, $offset = ''): self
    {
        if (array_is_list($this->storage) && !$offset) {
            $this->storage[] = $value;
        } else {
            $this->storage[$offset] = $value;
        }
        return $this;
    }

    /**
     * Returns the elements count.
     */
    public function length(): int
    {
        return count($this->storage);
    }

    /**
     * Appends elements to the collection.
     */
    public function append(...$elements): self
    {
        $this->storage = [...$this->storage, ...$elements];
        return $this;
    }

    /**
     * Prepends elements to the collection.
     */
    public function prepend(...$elements): self
    {
        $this->storage = [...$elements, ...$this->storage];
        return $this;
    }

    /**
     * Removes an element with index $index.
     *
     * On lists, this rearrange the numeric indexes.
     */
    public function remove($index)
    {
        // Si el array está vacío, no hacemos nada
        if (!$this->storage) {
            return;
        }

        if (array_is_list($this->storage)) {
            array_splice($this->storage, $index, 1);
        } else {
            unset($this->storage[$index]);
        }
    }

    /**
     * Filters elements and returns a new collection
     */
    public function filter(?Callable $callback = null): self
    {
        return new self(array_filter($this->storage, $callback));
    }

    /**
     * Filters element's keys and returns a new collection
     */
    public function filterKey(?Callable $callback = null): self
    {
        return new self(array_filter($this->storage, $callback, ARRAY_FILTER_USE_KEY));
    }

    /**
     * Returns a collection with every element passed via a callback
     */
    public function map(Callable $callback): self
    {
        return new self(array_map($callback, $this->storage));
    }

    /**
     * Arrayable implementation
     */
    public function asArray(): array
    {
        return $this->storage;
    }

    /**
     * Returns the array when called via var_dump() and friends.
     */
    public function __debugInfo()
    {
        return $this->storage;
    }

    /**
     * ArrayAccess::offsetExists — Whether an offset exists
     */
    public function offsetExists(mixed $offset): bool
    {
        return key_exists($offset, $this->storage);
    }

    /**
     * ArrayAccess::offsetGet — Offset to retrieve
     */
    public function &offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * ArrayAccess::offsetSet — Assign a value to the specified offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($value, $offset);
    }

    /**
     * ArrayAccess::offsetUnset — Unset an offset
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    /**
     * Countable::count — Count elements of an object
     */
    public function count(): int
    {
        return $this->length();
    }

    /**
     * Iterator::current — Return the current element
     */
    public function current(): mixed
    {
        return current($this->storage);
    }

    /**
     * Iterator::key — Return the key of the current element
     */
    public function key(): mixed
    {
        return key($this->storage);
    }

    /**
     * Iterator::next — Move forward to next element
     */
    public function next(): void
    {
        next($this->storage);
    }

    /**
     * Iterator::rewind — Rewind the Iterator to the first element
     */
    public function rewind(): void
    {
        reset($this->storage);
    }

    /**
     * Iterator::valid — Checks if current position is valid
     */
    public function valid(): bool
    {
        return current($this->storage) !== false;
    }
}
