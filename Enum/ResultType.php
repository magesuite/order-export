<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Enum;

enum ResultType: string
{
    case SUCCESS = 'success';
    case PARTIAL_SUCCESS = 'partial success';
    case FAILURE = 'failure';
}
