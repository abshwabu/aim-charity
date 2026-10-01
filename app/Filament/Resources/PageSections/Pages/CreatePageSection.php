<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageSections\Pages;

use App\Filament\Resources\PageSections\PageSectionResource;
use App\Models\PageSection;
use App\Support\SectionTypes;
use Filament\Resources\Pages\CreateRecord;

class CreatePageSection extends CreateRecord
{
    protected static string $resource = PageSectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $type = $data['type'] ?? '';
        $defaultContent = SectionTypes::defaultContent($type);

        $data['content'] = array_merge($defaultContent, $data['content'] ?? []);

        if (empty($data['sort_order'])) {
            $data['sort_order'] = (PageSection::query()->max('sort_order') ?? 0) + 1;
        }

        if (empty($data['anchor']) && ! empty($data['key'])) {
            $data['anchor'] = $data['key'];
        }

        return $data;
    }
}
