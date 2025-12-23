<?php

namespace Modules\Payment;

use Illuminate\Support\Str;

class InMemoryGateway implements PaymentGateway
{

    public function charge(PaymentDetails $details): SuccessfulPayment
    {
        return new SuccessfulPayment(
            Str::uuid()->toString(),
            $details->amountInCents,
            $this->id()
        );
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::InMemory;
    }
}
