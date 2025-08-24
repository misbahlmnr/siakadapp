<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    jadwal: any;
}>();

console.log(props.jadwal);

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
            <h1 class="text-2xl font-bold">Detail Jadwal Pelajaran</h1>

            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-3xl md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Kelas</p>
                    <p class="font-semibold">{{ jadwal.kelas.nama_kelas }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Mata Pelajaran</p>
                    <p class="font-semibold">{{ jadwal.mata_pelajaran.nama_mapel }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Guru</p>
                    <p class="font-semibold">{{ jadwal.guru?.user.name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Hari</p>
                    <p class="font-semibold">{{ jadwal.hari }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Jam</p>
                    <p class="font-semibold">{{ jadwal.jam_mulai.slice(0, 5) }} - {{ jadwal.jam_selesai.slice(0, 5) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Semester</p>
                    <p class="font-semibold">{{ jadwal.semester_ajaran.semester }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Tahun Ajaran</p>
                    <p class="font-semibold">{{ jadwal.semester_ajaran.tahun_ajaran }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <Button variant="outline" @click="$inertia.visit(route('admin.jadwal-pelajaran.index'))"> Kembali </Button>
                <Button @click="$inertia.visit(route('admin.jadwal-pelajaran.edit', jadwal.id))"> Edit Data </Button>
            </div>
        </div>
    </AppLayout>
</template>
