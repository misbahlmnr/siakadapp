<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

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
            <h1 class="text-2xl font-bold">Detail Data Guru</h1>

            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-2xl md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Nama Lengkap</p>
                    <p class="font-semibold">{{ user.name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Email</p>
                    <p class="font-semibold">{{ user.email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">NIP</p>
                    <p class="font-semibold">{{ user.guru_profile?.nip ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Mata Pelajaran</p>
                    <p class="font-semibold">{{ user.guru_profile?.mapel ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Status Guru</p>
                    <p class="font-semibold capitalize">{{ user.guru_profile?.status_guru ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Tanggal Masuk</p>
                    <p class="font-semibold">{{ user.guru_profile?.tanggal_masuk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Golongan</p>
                    <p class="font-semibold">{{ user.guru_profile?.golongan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">No. Telepon</p>
                    <p class="font-semibold">{{ user.guru_profile?.no_telp ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-400">Alamat</p>
                    <p class="font-semibold">{{ user.guru_profile?.alamat ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <Button variant="outline" @click="$inertia.visit(route('admin.users.index', 'guru'))"> Kembali </Button>
                <Button @click="$inertia.visit(route('admin.users.edit', { role: 'guru', id: user.id }))"> Edit Data </Button>
            </div>
        </div>
    </AppLayout>
</template>
