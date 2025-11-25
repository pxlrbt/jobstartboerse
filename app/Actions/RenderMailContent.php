<?php

namespace App\Actions;

use App\Models\Exhibitor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;

class RenderMailContent
{
    /**
     * @param  array<mixed>|string  $content
     */
    public function __invoke(array|string $content, Exhibitor $exhibitor): string
    {
        return RichContentRenderer::make($content)->mergeTags([
            'titel' => $exhibitor->contactPerson->title ?? '',
            'vorname' => $exhibitor->contactPerson->first_name ?? '',
            'nachname' => $exhibitor->contactPerson->last_name ?? '',
            'ausstellername' => $exhibitor->display_name,
        ])->toHtml();
    }
}
