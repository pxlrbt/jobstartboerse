<?php

namespace App\DataObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;

/**
 * @implements Arrayable<string, string>
 */
final class JobFairManager implements Arrayable
{
    /**
     * @param  array<string>|string  $file
     */
    public function __construct(
        public string $name,
        public string $email,
    ) {}

    public function mailto(): HtmlString
    {
        return new HtmlString(<<<HTML
            <a class="underline" href="mailto:{$this->email}">{$this->name}</a>
        HTML);
    }

    public static function freiburg(): self
    {
        return new self(
            name: 'das Team Job-Start-Börse Freiburg',
            email: 'jobstartboerse@fwtm.de'
        );
    }

    public static function regional(): self
    {
        return new self(
            name: 'Frau Julia Schwab (BZ)',
            email: 'umland-jobstartboerse@bz-medien.de'
        );
    }

    /**
     * @param  array<string, string>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
        );
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
