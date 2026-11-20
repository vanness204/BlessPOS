<?php

// Namespace menunjukkan lokasi model User di dalam aplikasi Laravel.
namespace App\Models;

// Mengimpor UserFactory untuk kebutuhan pembuatan data dummy.
use Database\Factories\UserFactory;

// Mengimpor atribut untuk menentukan kolom yang boleh diisi secara mass assignment.
use Illuminate\Database\Eloquent\Attributes\Fillable;

// Mengimpor atribut untuk menyembunyikan kolom sensitif dari hasil serialisasi.
use Illuminate\Database\Eloquent\Attributes\Hidden;

// Mengimpor fitur factory pada model.
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Mengimpor model autentikasi bawaan Laravel.
use Illuminate\Foundation\Auth\User as Authenticatable;

// Mengimpor fitur notifikasi Laravel.
use Illuminate\Notifications\Notifiable;

// Mengimpor tipe relasi belongsTo.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Menentukan kolom yang boleh diisi melalui mass assignment.
// Kolom organisasi dan role nantinya harus diatur melalui proses
// administrasi yang memiliki hak akses, bukan dari registrasi publik.
#[Fillable([
    'name',
    'email',
    'password',
    'role_id',
    'foodcourt_id',
    'tenant_id',
    'is_active',
    'last_login_at',
])]

// Menyembunyikan password dan remember_token dari output model.
#[Hidden([
    'password',
    'remember_token',
])]

class User extends Authenticatable
{
    /**
     * Mengaktifkan fitur factory dan notifikasi Laravel.
     *
     * @use HasFactory<UserFactory>
     */
    use HasFactory, Notifiable;

    /**
     * Mengatur tipe data beberapa kolom secara otomatis.
     */
    protected function casts(): array
    {
        return [
            // Mengubah email_verified_at menjadi objek tanggal.
            'email_verified_at' => 'datetime',

            // Menggunakan mekanisme hashing otomatis untuk password.
            'password' => 'hashed',

            // Mengubah status aktif menjadi boolean.
            'is_active' => 'boolean',

            // Mengubah waktu login terakhir menjadi objek tanggal.
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relasi pengguna dengan Role.
     * Satu pengguna memiliki satu role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi pengguna dengan Foodcourt.
     * Pengguna dapat terhubung dengan satu foodcourt.
     */
    public function foodcourt(): BelongsTo
    {
        return $this->belongsTo(Foodcourt::class);
    }

    /**
     * Relasi pengguna dengan Tenant.
     * Pengguna dapat terhubung dengan satu tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Memeriksa apakah pengguna memiliki permission tertentu.
     *
     * Parameter:
     * $permission = slug permission yang ingin diperiksa.
     *
     * Mengembalikan:
     * true  = pengguna memiliki permission.
     * false = pengguna tidak memiliki permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Pengguna tidak boleh mengakses fitur jika tidak aktif
        // atau belum mempunyai role.
        if (!$this->is_active || !$this->role) {
            return false;
        }

        // Memeriksa apakah role pengguna memiliki permission
        // dengan slug yang diminta.
        return $this->role
            ->permissions()
            ->where('slug', $permission)
            ->exists();
    }
}
