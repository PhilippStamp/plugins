<?php

namespace Boy132\LegalPages\Filament\App\Pages;

use Boy132\LegalPages\Enums\LegalPageType;

class PrivacyPolicy extends BaseLegalPage
{
    public function getPageType(): LegalPageType
    {
        return LegalPageType::PrivacyPolicy;
    }
}
