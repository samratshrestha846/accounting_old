<?php
namespace App\Enums;

use App\Abstracts\Enum;

class OrderItemStatus extends Enum {
    const CANCLED = 0;
    const PENDING = 1;
    const READY = 2;
    const SERVED = 3;
    const SUSPENDED = 4;
}
