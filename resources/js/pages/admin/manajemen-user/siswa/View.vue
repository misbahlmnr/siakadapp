<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{
    siswa: {
        nis: string;
        nama: string;
        jenis_kelamin: string;
        kelas: string;
        tahun_masuk: string;
        ttl: string;
        alamat: string;
        kontak_ortu: string;
        status: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Akademik', href: '#' },
    { title: 'Siswa', href: '/akademik/siswa' },
    { title: 'Detail Data Siswa', href: '#' },
];
</script>

<template>
    <Head title="Detail Data Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-10 py-6">
            <h1 class="mb-6 text-2xl font-bold">Detail Data Siswa</h1>

            <div class="grid grid-cols-1 gap-6 rounded-xl border p-6 shadow md:grid-cols-2 dark:bg-[#121212]">
                <div class="bgap">
                    <p class="text-sm text-gray-600">NIS</p>
                    <p class="text-base font-medium">{{ siswa.nis }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                    <p class="text-base font-medium">{{ siswa.nama }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Jenis Kelamin</p>
                    <p class="text-base font-medium capitalize">{{ siswa.jenis_kelamin }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Kelas</p>
                    <p class="text-base font-medium">{{ siswa.kelas }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Tahun Masuk</p>
                    <p class="text-base font-medium">{{ siswa.tahun_masuk }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Tempat, Tanggal Lahir</p>
                    <p class="text-base font-medium">{{ siswa.ttl }}</p>
                </div>

                <div class="bgap md:col-span-2">
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="text-base font-medium whitespace-pre-line">{{ siswa.alamat }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Kontak Orang Tua</p>
                    <p class="text-lg font-medium">{{ siswa.kontak_ortu }}</p>
                </div>

                <div class="bgap">
                    <p class="text-sm text-gray-600">Status</p>
                    <p
                        class="inline-block max-w-max rounded-full px-3 py-1 text-sm font-semibold"
                        :class="siswa.status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                    >
                        {{ siswa.status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
