<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    mataPelajaran: {
        id: number;
        kode_mapel: string;
        nama_mapel: string;
        deskripsi: string | null;
        guru: {
            id: number;
            name: string;
        } | null;
        created_at: string;
        updated_at: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Mata Pelajaran', href: route('admin.mata-pelajaran.index') },
    { title: `Detail ${props.mataPelajaran.nama_mapel}`, href: route('admin.mata-pelajaran.show', props.mataPelajaran.id) },
];
</script>

<template>
    <Head :title="`Detail Mata Pelajaran - ${mataPelajaran.nama_mapel}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <h1 class="text-2xl font-bold">Detail Mata Pelajaran</h1>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Kode Mapel</p>
                    <p class="font-semibold">{{ mataPelajaran.kode_mapel }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Nama Mapel</p>
                    <p class="font-semibold">{{ mataPelajaran.nama_mapel }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Guru Pengampu</p>
                    <p class="font-semibold">
                        {{ mataPelajaran.guru?.name ?? '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Deskripsi</p>
                    <p class="font-semibold whitespace-pre-line">
                        {{ mataPelajaran.deskripsi || '-' }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <Button variant="outline" @click="$inertia.visit(route('admin.mata-pelajaran.index'))"> Kembali </Button>
                <Button @click="$inertia.visit(route('admin.mata-pelajaran.edit', mataPelajaran.id))"> Edit Data </Button>
            </div>
        </div>
    </AppLayout>
</template>
