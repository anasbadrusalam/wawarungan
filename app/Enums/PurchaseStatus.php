<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case Draft = 'draft';
    case Ordered = 'ordered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
