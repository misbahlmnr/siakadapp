<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    role: {
        type: String,
        required: true,
    },
});

const jenisKelaminOpt = {
    L: 'Laki-laki',
    P: 'Perempuan',
    null: '-',
} as const;

type JenisKelaminKey = keyof typeof jenisKelaminOpt;

const statusOpt = {
    aktif: 'Aktif',
    lulus: 'Lulus',
    pindah: 'Pindah',
    null: '-',
} as const;

type StatusKey = keyof typeof statusOpt;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Siswa', href: route('admin.users.index', 'siswa') },
    { title: `Detail ${props.user.name}`, href: route('admin.users.show', { role: props.role, id: props.user.id }) },
];
</script>

<template>
    <Head :title="`Detail Siswa - ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.visit(route('admin.users.index', 'siswa'))" class="h-8 w-8">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Data Siswa</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap data siswa</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="router.visit(route('admin.users.edit', { role: 'siswa', id: user.id }))"> Edit Data </Button>
                </div>
            </div>

            <!-- Informasi Siswa -->
            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-3xl md:grid-cols-2">
                <!-- Nama -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Nama Lengkap</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.name }}</p>
                </div>

                <!-- Email -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Email</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.email }}</p>
                </div>

                <!-- NIS -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">NIS</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.nis ?? '-' }}</p>
                </div>

                <!-- NISN -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">NISN</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.nisn ?? '-' }}</p>
                </div>

                <!-- Kelas -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Kelas</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.kelas?.nama_kelas ?? '-' }}</p>
                </div>

                <!-- Angkatan -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Angkatan</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.angkatan ?? '-' }}</p>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Jenis Kelamin</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ jenisKelaminOpt[(user.siswa_profile?.jenis_kelamin ?? 'null') as JenisKelaminKey] }}
                    </p>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Tempat Lahir</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.tempat_lahir ?? '-' }}</p>
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Tanggal Lahir</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.tanggal_lahir ?? '-' }}</p>
                </div>

                <!-- No HP -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">No. HP</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.no_hp ?? '-' }}</p>
                </div>

                <!-- Nama Orang Tua -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Nama Orang Tua</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.nama_ortu ?? '-' }}</p>
                </div>

                <!-- Kontak Orang Tua -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Kontak Orang Tua</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.kontak_ortu ?? '-' }}</p>
                </div>

                <!-- Status -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Status</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ statusOpt[(user.siswa_profile?.status ?? 'null') as StatusKey] }}
                    </p>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-400 dark:text-gray-400">Alamat</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.siswa_profile?.alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
