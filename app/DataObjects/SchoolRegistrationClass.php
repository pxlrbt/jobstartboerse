<?php

namespace App\DataObjects;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string>
 */
final class SchoolRegistrationClass implements Arrayable
{
    public function __construct(
        public string $name,
        public string $time,
        public int|string $students_count,
    ) {}

    /**
     * @param  array<string, string>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
