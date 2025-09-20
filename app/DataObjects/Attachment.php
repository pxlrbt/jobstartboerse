<?php

namespace App\DataObjects;

use App\Enums\AttachmentCategory;
use Illuminate\Contracts\Support\Arrayable;

final class Attachment implements Arrayable
{
    public function __construct(
        public array|string $file,
        public ?string $display_name,
        public AttachmentCategory|string|null $category
    ) {}

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
