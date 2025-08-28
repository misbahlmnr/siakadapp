<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    jadwal: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Jadwal Pelajaran', href: route('admin.jadwal-pelajaran.index') },
    { title: `Detail Jadwal #${props.jadwal.id}`, href: route('admin.jadwal-pelajaran.show', props.jadwal.id) },
];
</script>

<template>
    <Head :title="`Detail Jadwal Pelajaran - ${jadwal.kelas.nama_kelas}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.visit(route('admin.jadwal-pelajaran.index'))" class="h-8 w-8">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Jadwal Pelajaran</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap jadwal pembelajaran</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="router.visit(route('admin.jadwal-pelajaran.edit', jadwal.id))"> Edit Data </Button>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-3xl md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Kelas</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ jadwal.kelas.nama_kelas }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Mata Pelajaran</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ jadwal.mata_pelajaran.nama_mapel }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Guru</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ jadwal.guru?.user.name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Hari</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ jadwal.hari }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Jam</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ jadwal.jam_mulai.slice(0, 5) }} - {{ jadwal.jam_selesai.slice(0, 5) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Semester</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ jadwal.semester_ajaran.semester }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Tahun Ajaran</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ jadwal.semester_ajaran.tahun_ajaran }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
