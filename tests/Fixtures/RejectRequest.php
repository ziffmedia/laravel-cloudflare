<?php

namespace Tests\Fixtures;

/**
 * Stands in for any extra middleware an app adds to its Nova API middleware.
 */
class RejectRequest
{
    public function handle(): never
    {
        abort(418);
    }
}
