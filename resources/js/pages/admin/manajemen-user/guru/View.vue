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

const statusKepegawaianOpt = {
    pns: 'PNS',
    honorer: 'Honorer',
    null: '-',
} as const;

type StatusKepegawaianKey = keyof typeof statusKepegawaianOpt;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Guru', href: route('admin.users.index', 'guru') },
    { title: `Detail ${props.user.name}`, href: route('admin.users.show', { role: props.role, id: props.user.id }) },
];
</script>

<template>
    <Head :title="`Detail Guru - ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.visit(route('admin.users.index', 'guru'))" class="h-8 w-8">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Data Guru</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap data guru</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="router.visit(route('admin.users.edit', { role: 'guru', id: user.id }))"> Edit Data </Button>
                </div>
            </div>

            <!-- Informasi Umum -->
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

                <!-- NIP -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">NIP</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.guru_profile?.nip ?? '-' }}</p>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Jenis Kelamin</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ jenisKelaminOpt[(user.guru_profile?.jenis_kelamin ?? 'null') as JenisKelaminKey] }}
                    </p>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Tempat Lahir</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.guru_profile?.tempat_lahir ?? '-' }}</p>
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Tanggal Lahir</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.guru_profile?.tanggal_lahir ?? '-' }}</p>
                </div>

                <!-- Status Kepegawaian -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Status Kepegawaian</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ statusKepegawaianOpt[(user.guru_profile?.status_kepegawaian ?? 'null') as StatusKepegawaianKey] }}
                    </p>
                </div>

                <!-- No HP -->
                <div>
                    <p class="text-sm text-gray-400 dark:text-gray-400">No. HP</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.guru_profile?.no_hp ?? '-' }}</p>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-400 dark:text-gray-400">Alamat</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ user.guru_profile?.alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
