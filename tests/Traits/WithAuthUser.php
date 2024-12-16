<?php

namespace Tests\Traits;

use App\Models\User;
use App\Enums\Types;

trait WithAuthUser
{
    protected User $authUser;

    protected function createAndAuthenticateUser(array $attributes = []): User
    {
        $this->authUser = User::factory()->create(array_merge([
            'type' => Types::nat,
            'email_verified_at' => now(),
        ], $attributes));

        $this->actingAs($this->authUser);

        return $this->authUser;
    }

    protected function createAndAuthenticateAdmin(array $attributes = []): User
    {
        return $this->createAndAuthenticateUser(array_merge([
            'is_admin' => true,
        ], $attributes));
    }
}
