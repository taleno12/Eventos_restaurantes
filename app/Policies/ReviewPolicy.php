<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;

class ReviewPolicy
{
    private function isAdmin(User $user): bool
    {
        return $user->id === 1 || $user->email === 'admin@turismo.ni';
    }

    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $this->isAdmin($user);
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $this->isAdmin($user);
    }

    public function boot(): void
{
    Gate::policy(Review::class, ReviewPolicy::class);
}

}