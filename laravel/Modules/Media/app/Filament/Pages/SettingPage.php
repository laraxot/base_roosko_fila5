<?php

declare(strict_types=1);

namespace Modules\Media\Filament\Pages;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
<<<<<<< HEAD
<<<<<<< .merge_file_NcfD3v
<<<<<<< .merge_file_uQVW2Q
=======
use Modules\Media\Actions\Upload\GetEffectiveUploadLimitAction;
>>>>>>> .merge_file_DU5LLJ
=======
use Modules\Media\Actions\Upload\GetEffectiveUploadLimitAction;
>>>>>>> .merge_file_oj5g34
=======
use Modules\Media\Actions\Upload\GetEffectiveUploadLimitAction;
>>>>>>> laraxot/dev
use Modules\Media\Datas\MediaConfigData;
use Modules\Xot\Filament\Pages\BaseSettingPage;

/**
 * Impostazioni di Media (`config/{tenant}/media.php`), solo super-admin: dimensione massima e tipi accettati degli allegati delle Resource.
 *
 * @see MediaConfigData
 */
class SettingPage extends BaseSettingPage
{
    /**
     * @return array<string, TagsInput|TextInput>
     */
    protected function getFormSchema(): array
    {
        return [
            'attachment_max_size_kb' => TextInput::make('attachment_max_size_kb')
                ->numeric()
                ->integer()
                ->minValue(1)
<<<<<<< HEAD
<<<<<<< .merge_file_NcfD3v
<<<<<<< .merge_file_uQVW2Q
                ->required(),
            'attachment_mime_types' => TagsInput::make('attachment_mime_types')
=======
=======
>>>>>>> .merge_file_oj5g34
=======
>>>>>>> laraxot/dev
                ->maxValue(fn (): int => intdiv(app(GetEffectiveUploadLimitAction::class)->execute(), 1024))
                ->required(),
            'attachment_mime_types' => TagsInput::make('attachment_mime_types')
                ->nestedRecursiveRules(['regex:#^[a-z0-9.+-]+/([a-z0-9.+-]+|\*)$#i'])
<<<<<<< HEAD
<<<<<<< .merge_file_NcfD3v
>>>>>>> .merge_file_DU5LLJ
=======
>>>>>>> .merge_file_oj5g34
=======
>>>>>>> laraxot/dev
                ->required(),
        ];
    }
}
