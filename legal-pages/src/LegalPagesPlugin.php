<?php

namespace Boy132\LegalPages;

use Boy132\LegalPages\Enums\LegalPageType;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;

class LegalPagesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'legal-pages';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        $panel->discoverPages(plugin_path($this->getId(), "src/Filament/$id/Pages"), "Boy132\\LegalPages\\Filament\\$id\\Pages");
    }

    public function boot(Panel $panel): void {}

    public static function Save(LegalPageType $type, ?string $contents): bool
    {
        $path = $type->getId() . '.md';

        if (!$contents) {
            return Storage::delete($path);
        }

        return Storage::put($path, $contents);
    }

    public static function Load(LegalPageType $type): ?string
    {
        return Storage::get($type->getId() . '.md');
    }
}
