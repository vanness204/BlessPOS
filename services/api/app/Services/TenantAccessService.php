<?php

namespace App\Services;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Role;
use Illuminate\Validation\ValidationException;

class TenantAccessService
{
    /**
     * Memastikan karyawan, outlet, dan role
     * berasal dari tenant yang sama.
     *
     * $tenantId harus berasal dari konteks
     * autentikasi server, bukan request pengguna.
     */
    public function validateAssignment(
        int $tenantId,
        int $userId,
        int $outletId,
        ?int $roleId = null
    ): array {
        $user = User::query()
            ->where('tenant_id', $tenantId)
            ->find($userId);

        if (!$user) {
            throw ValidationException::withMessages([
                'user_id' => 'Karyawan tidak ditemukan atau bukan milik bisnis ini.',
            ]);
        }

        $outlet = Outlet::query()
            ->where('tenant_id', $tenantId)
            ->find($outletId);

        if (!$outlet) {
            throw ValidationException::withMessages([
                'outlet_id' => 'Outlet tidak ditemukan atau bukan milik bisnis ini.',
            ]);
        }

        $role = null;

        if ($roleId !== null) {
            $role = Role::query()
                ->where('tenant_id', $tenantId)
                ->find($roleId);

            if (!$role) {
                throw ValidationException::withMessages([
                    'role_id' => 'Role tidak ditemukan atau bukan milik bisnis ini.',
                ]);
            }
        }

        return [
            'user' => $user,
            'outlet' => $outlet,
            'role' => $role,
        ];
    }
}
