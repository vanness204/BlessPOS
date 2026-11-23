<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'Dashboard' => [
                ['dashboard.view', 'Lihat Dashboard'],
            ],

            'POS' => [
                ['pos.view', 'Lihat POS'],
                ['pos.create', 'Buat Transaksi POS'],
                ['pos.cancel', 'Batalkan Transaksi POS'],
                ['pos.refund', 'Proses Refund POS'],
            ],

            'Sales' => [
                ['sales.view', 'Lihat Penjualan'],
                ['sales.create', 'Buat Penjualan'],
                ['sales.update', 'Ubah Penjualan'],
                ['sales.cancel', 'Batalkan Penjualan'],
            ],

            'Products' => [
                ['products.view', 'Lihat Produk'],
                ['products.create', 'Tambah Produk'],
                ['products.update', 'Ubah Produk'],
                ['products.delete', 'Hapus Produk'],
            ],

            'Categories' => [
                ['categories.view', 'Lihat Kategori'],
                ['categories.create', 'Tambah Kategori'],
                ['categories.update', 'Ubah Kategori'],
                ['categories.delete', 'Hapus Kategori'],
            ],

            'Inventory' => [
                ['inventory.view', 'Lihat Inventaris'],
                ['inventory.adjust', 'Sesuaikan Stok'],
                ['inventory.transfer', 'Transfer Stok'],
            ],

            'Warehouses' => [
                ['warehouses.view', 'Lihat Gudang'],
                ['warehouses.create', 'Tambah Gudang'],
                ['warehouses.update', 'Ubah Gudang'],
                ['warehouses.delete', 'Hapus Gudang'],
            ],

            'Purchasing' => [
                ['purchases.view', 'Lihat Pembelian'],
                ['purchases.create', 'Buat Pembelian'],
                ['purchases.update', 'Ubah Pembelian'],
                ['purchases.approve', 'Setujui Pembelian'],
                ['purchases.cancel', 'Batalkan Pembelian'],
            ],

            'Suppliers' => [
                ['suppliers.view', 'Lihat Supplier'],
                ['suppliers.create', 'Tambah Supplier'],
                ['suppliers.update', 'Ubah Supplier'],
                ['suppliers.delete', 'Hapus Supplier'],
            ],

            'Cash Management' => [
                ['cash.view', 'Lihat Manajemen Kas'],
                ['cash.open', 'Buka Kas'],
                ['cash.close', 'Tutup Kas'],
                ['cash.adjust', 'Sesuaikan Kas'],
            ],

            'Expenses' => [
                ['expenses.view', 'Lihat Pengeluaran'],
                ['expenses.create', 'Tambah Pengeluaran'],
                ['expenses.update', 'Ubah Pengeluaran'],
                ['expenses.delete', 'Hapus Pengeluaran'],
                ['expenses.approve', 'Setujui Pengeluaran'],
            ],

            'Employees' => [
                ['employees.view', 'Lihat Karyawan'],
                ['employees.create', 'Tambah Karyawan'],
                ['employees.update', 'Ubah Karyawan'],
                ['employees.delete', 'Hapus Karyawan'],
            ],

            'Attendance' => [
                ['attendance.view', 'Lihat Absensi'],
                ['attendance.manage', 'Kelola Absensi'],
                ['attendance.approve', 'Setujui Absensi'],
            ],

            'Shifts' => [
                ['shifts.view', 'Lihat Shift'],
                ['shifts.create', 'Tambah Shift'],
                ['shifts.update', 'Ubah Shift'],
                ['shifts.delete', 'Hapus Shift'],
            ],

            'Reports' => [
                ['reports.view', 'Lihat Laporan'],
                ['reports.export', 'Ekspor Laporan'],
            ],

            'Users & Roles' => [
                ['users.view', 'Lihat Pengguna'],
                ['users.create', 'Tambah Pengguna'],
                ['users.update', 'Ubah Pengguna'],
                ['users.delete', 'Hapus Pengguna'],
                ['roles.manage', 'Kelola Role dan Permission'],
            ],

            'Outlets' => [
                ['outlets.view', 'Lihat Outlet'],
                ['outlets.create', 'Tambah Outlet'],
                ['outlets.update', 'Ubah Outlet'],
                ['outlets.delete', 'Hapus Outlet'],
            ],

            'Settings' => [
                ['settings.view', 'Lihat Pengaturan'],
                ['settings.update', 'Ubah Pengaturan'],
            ],

            'Notifications' => [
                ['notifications.view', 'Lihat Notifikasi'],
                ['notifications.manage', 'Kelola Notifikasi'],
            ],
        ];

        foreach ($permissions as $module => $items) {
            foreach ($items as [$slug, $name]) {
                Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'module' => $module,
                        'name' => $name,
                    ]
                );
            }
        }
    }
}