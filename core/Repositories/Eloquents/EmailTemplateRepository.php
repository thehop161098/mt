<?php

namespace Core\Repositories\Eloquents;

use App\Models\EmailTemplate;
use Core\Repositories\Contracts\EmailTemplateInterface;

class EmailTemplateRepository implements EmailTemplateInterface
{
    public function find($code)
    {
        return EmailTemplate::firstWhere('code', $code);
    }
}
