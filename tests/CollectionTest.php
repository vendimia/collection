<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Vendimia\Collection\Collection;

require __DIR__ . '/../vendor/autoload.php';

final class CollectionTest extends TestCase
{
    public function testInitializeCollection(): Collection
    {
        $test = new Collection(['orange', 'apple', 'banana']);

        $this->assertEquals($test->asArray(), ['orange', 'apple', 'banana']);

        return $test;
    }

    public function testInitializeCollectionWithDict(): Collection
    {
        $test = [
            'name' => 'Oliver',
            'age' => '40',
            'address' => '123 Testing St.'
        ];

        $collection = new Collection($test);

        $this->assertEquals(
            $test,
            $collection->asArray()
        );

        return $collection;
    }

    /**
     * @depends testInitializeCollection
     */
    public function testLength(Collection $collection)
    {
        $this->assertEquals($collection->length(), 3);
    }

    /**
     * @depends testInitializeCollection
     */
    public function testCount(Collection $collection)
    {
        $this->assertEquals(count($collection), count($collection->asArray()));
    }

    /**
     * @depends testInitializeCollection
     */
    public function testAppend(Collection $collection)
    {
        $collection->append('dragon fruit', 'carambola');

        $this->assertEquals($collection->asArray(), [
            'orange', 'apple', 'banana', 'dragon fruit', 'carambola'
        ]);
    }

    /**
     * @depends testInitializeCollection
     */
    public function testPrepend(Collection $collection)
    {
        $collection->prepend('pear', 'peach');

        $this->assertEquals($collection->asArray(), [
            'pear', 'peach', 'orange', 'apple', 'banana', 'dragon fruit', 'carambola'
        ]);
    }

    /**
     * @depends testInitializeCollection
     */
    public function testRemoveFromList(Collection $collection)
    {
        $collection->remove(2);

        $this->assertEquals($collection->asArray(), [
            'pear', 'peach', 'apple', 'banana', 'dragon fruit', 'carambola'
        ]);
    }

    /**
     * @depends testInitializeCollection
     */
    public function testGet(Collection $collection)
    {
        $this->assertEquals($collection->get(2), 'apple');
    }

    /**
     * @depends testInitializeCollection
     */
    public function testGetWithSquareBrackets(Collection $collection)
    {
        $this->assertEquals('apple', $collection[2]);
    }

    /**
     * @depends testInitializeCollection
     */
    public function testIterateElements(Collection $collection)
    {
        $result = [];
        foreach ($collection as $element) {
            $result[] = $element;
        }

        $this->assertEquals(
            ['pear', 'peach', 'apple', 'banana', 'dragon fruit', 'carambola'],
            $result
        );
    }

    /**
     * @depends testInitializeCollection
     */
    public function testAddViaSquareBrakets(Collection $collection)
    {
        $collection[] = 'strawberry'; // I know...

        $this->assertEquals([
            'pear', 'peach', 'apple', 'banana', 'dragon fruit', 'carambola',
            'strawberry',
        ], $collection->asArray());
    }


    /**
     * @depends testInitializeCollection
     */
    public function testFilterValues(Collection $collection)
    {
        $this->assertEquals(
            ['pear', 'peach'],
            $collection->filter(fn($x) => str_starts_with($x, 'p'))->asArray()
        );
    }

    /**
     * @depends testInitializeCollectionWithDict
     */
    public function testFilterKeys(Collection $collection)
    {
        $this->assertEquals(
            ['age' => 40, 'address' => '123 Testing St.'],
            $collection->filterKey(fn($x) => str_starts_with($x, 'a'))->asArray()
        );
    }

    public function testAccessElementAsProperty()
    {
        $collection = new Collection(['name' => 'oliver']);

        $this->assertEquals(
            'oliver',
            $collection->name
        );
    }

    public function testAccessPropertyAsKey()
    {
        $collection = new Collection;
        $collection->name = 'oliver';

        $this->assertEquals(
            'oliver',
            $collection['name']
        );
    }

    public function testElementAsReference()
    {
        $collection = new Collection(['value' => 10]);
        $collection['value']++;

        $this->assertEquals(
            11,
            $collection->value
        );
    }

    public function testPropertyAsReference()
    {
        $collection = new Collection(['value' => 10]);
        $collection->value++;

        $this->assertEquals(
            11,
            $collection['value']
        );
    }


    public function testMap()
    {
        $numbers = new Collection([2, 4, 6, 8]);

        $this->assertEquals(
            [4, 16, 36, 64],
            $numbers->map(fn($x) => $x * $x)->asArray(),
        );
    }

    /**
     * @depends testInitializeCollectionWithDict
     */
    public function testExtract(Collection $collection)
    {
        $this->assertEquals(
            ['name' => 'Oliver', 'age' => '40'],
            $collection->extract('name', 'age')->asArray()
        );
    }
}