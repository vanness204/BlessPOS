<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RolePermissionService
{
    /**
     * Membuat atau memastikan role Owner milik tenant.
     * Owner mendapatkan seluruh permission global.
     */
    public function ensureOwnerRole(int $tenantId): Role
    {
        return DB::transaction(function () use ($tenantId) {
            $role = Role::query()->updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'slug' => 'owner',
                ],
                [
                    'name' => 'Owner',
                    'description' => 'Pemilik bisnis dengan akses penuh ke seluruh fitur Bless POS.',
                    'is_system' => true,
                ]
            );

            $permissionIds = Permission::query()
                ->pluck('id')
                ->all();

            $role->permissions()->sync($permissionIds);

            return $role->load('permissions');
        });
    }

    /**
     * Mengatur permission role milik tenant.
     *
     * Role sistem tidak dapat diubah melalui fungsi ini.
     */
    public function syncPermissions(
        int $tenantId,
        int $roleId,
        array $permissionIds
    ): Role {
        return DB::transaction(function () use (
            $tenantId,
            $roleId,
            $permissionIds
        ) {
            $role = Role::query()
                ->where('tenant_id', $tenantId)
                ->lockForUpdate()
                ->find($roleId);

            if (!$role) {
                throw ValidationException::withMessages([
                    'role_id' => 'Role tidak ditemukan atau bukan milik bisnis ini.',
                ]);
            }

            if ($role->is_system) {
                throw ValidationException::withMessages([
                    'role_id' => 'Permission role sistem tidak dapat diubah.',
                ]);
            }

            $permissionIds = array_values(array_unique($permissionIds));

            $permissions = Permission::query()
                ->whereIn('id', $permissionIds)
                ->get();

            if ($permissions->count() !== count($permissionIds)) {
                throw ValidationException::withMessages([
                    'permission_ids' => 'Terdapat permission yang tidak valid.',
                ]);
            }

            $role->permissions()->sync($permissionIds);

            return $role->load('permissions');
        });
    }
}