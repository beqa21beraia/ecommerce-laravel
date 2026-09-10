<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    public function list(User $user): Collection
    {
        return $user->addresses()->orderByDesc('is_default')->get();
    }

    public function create(User $user, array $data): Address
    {
        if (!empty($data['is_default'])) {
            $this->clearExistingDefault($user);
        }

        return $user->addresses()->create($data);
    }

    public function update(Address $address, array $data): Address
    {
        if (!empty($data['is_default'])) {
            $this->clearExistingDefault($address->user);
        }

        $address->update($data);

        return $address;
    }

    public function delete(Address $address): void
    {
        $address->delete();
    }

    private function clearExistingDefault(User $user): void
    {
        $user->addresses()->where('is_default', true)->update(['is_default' => false]);
    }
}
