<?php

declare(strict_types=1);

namespace Struct\Struct;

use Struct\Contracts\StructCollectionInterface;
use Struct\Contracts\StructInterface;

class StructCollection implements StructCollectionInterface
{
    /**
     * @var array<StructInterface>
     */
    protected array $values = [];

    public function getValues(): array
    {
        return $this->values;
    }

    public function addValue(StructInterface $struct): void
    {
        $this->values[] = $struct;
    }

    public function count(): int
    {
        return count($this->values);
    }

    private int $currentIndex = 0;

    public function current(): StructInterface
    {
        return $this->values[$this->currentIndex];
    }

    public function next(): void
    {
        ++$this->currentIndex;
    }

    public function key(): int
    {
        return $this->currentIndex;
    }

    public function valid(): bool
    {
        if ($this->currentIndex < count($this->values)) {
            return true;
        }
        return false;
    }

    public function rewind(): void
    {
        $this->currentIndex = 0;
    }
}
