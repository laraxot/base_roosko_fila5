<?php

declare(strict_types=1);

namespace Modules\Notify\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Notify\Models\MailTemplate;

trait MailTemplateVersionRelations
{
    /**
     * @phpstan-return BelongsTo<MailTemplate, $this>
     */
    public function template(): BelongsTo
    {
<<<<<<< HEAD
        return $this->belongsTo(MailTemplate::class, 'template_id');
=======
        return $this->belongsTo(MailTemplate::class, 'mail_template_id');
>>>>>>> laraxot/dev
    }
}
