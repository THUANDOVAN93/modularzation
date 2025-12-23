<?php

namespace Modules\Payment;

use Illuminate\Support\Number;
use Illuminate\Support\Str;

class PayBuddySdk
{

    public function charge(string $token, int $amountInCents, string $statementDescription): array
    {
        $this->validateToken($token);

        $localizedAmount = Number::currency($amountInCents, 'USD');

        return [
            'id' => (string) Str::uuid(),
            'amount_in_cents' => $amountInCents,
            'localized_amount' => $localizedAmount,
            'statement_description' => $statementDescription,
            'created_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * @throws \RuntimeException
     */
    public function validateToken(string $token): void
    {
        if (! Str::isUuid($token)) {
            throw new \RuntimeException('The given payment token is invalid.');
        }
    }

    public static function make(): PayBuddySdk
    {
        return new self();
    }

    public static function validToken(): string
    {
        return (string) Str::uuid();
    }

    public static function invalidToken(): string
    {
        return substr(self::validToken(), -35);
    }
}
