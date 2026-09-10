<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(protected AddressService $addressService) {}

    public function index(Request $request)
    {
        return AddressResource::collection(
            $this->addressService->list($request->user())
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'address_line' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        return new AddressResource(
            $this->addressService->create($request->user(), $validated)
        );
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeOwnership($request, $address);

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'recipient_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'city' => 'sometimes|string|max:255',
            'address_line' => 'sometimes|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        return new AddressResource(
            $this->addressService->update($address, $validated)
        );
    }

    public function destroy(Request $request, Address $address)
    {
        $this->authorizeOwnership($request, $address);

        $this->addressService->delete($address);

        return response(status: 204);
    }

    private function authorizeOwnership(Request $request, Address $address): void
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403, 'This address does not belong to you.');
        }
    }
}
