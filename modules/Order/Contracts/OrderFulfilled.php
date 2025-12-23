<?php

namespace Modules\Order\Contracts;

use Modules\User\UserDto;

class OrderFulfilled
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
    )
    {
    }
}
