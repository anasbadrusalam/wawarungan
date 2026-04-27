<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PurchaseStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Ordered = 'ordered';
    case Received = 'completed';
    case Cancelled = 'cancelled';

    public function getLabel(): string | Htmlable | null
    {    
        return match ($this) {
            self::Draft => 'Draft',
            self::Ordered => 'Ordered',
            self::Received => 'Received',
            self::Cancelled => 'Cancelled',
        };
    }
}
