<?php

namespace App\DataObjects;

use App\Enums\AttachmentCategory;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string>
 */
final class Attachment implements Arrayable
{
    /**
     * @param  array<string>|string  $file
     */
    public function __construct(
        public array|string $file,
        public ?string $display_name,
        public AttachmentCategory|string|null $category
    ) {}

    /**
     * @param  array<string, string>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            file: $data['file'],
            display_name: $data['display_name'],
            category: AttachmentCategory::tryFrom($data['category']),
        );
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
