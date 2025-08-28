<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { MateriPelajaran } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, ExternalLink, File, FileDown, Link as LinkIcon } from 'lucide-vue-next';

const props = defineProps<{
    jadwal: {
        nama_mapel: string;
        kelas: string;
    };
    guru: {
        nama: string;
    };
    materi: MateriPelajaran;
}>();
</script>

<template>
    <Head :title="`Detail Materi: ${props.materi.judul_materi}`" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
            { title: 'Materi Pembelajaran', href: route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.materi.jadwal_id }) },
            { title: props.materi.judul_materi, href: '#' },
        ]"
    >
        <div class="flex flex-col gap-6 px-8 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button
                        variant="ghost"
                        size="icon"
                        @click="router.visit(route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.materi.jadwal_id }))"
                        class="h-8 w-8"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Materi Pembelajaran</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap materi pembelajaran</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        @click="router.visit(route('admin.jadwal-pelajaran.edit', { jadwal_id: props.materi.jadwal_id, materi_id: props.materi.id }))"
                    >
                        Edit Data
                    </Button>
                </div>
            </div>

            <!-- Informasi Umum -->
            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-3xl md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Pertemuan Ke</p>
                    <p class="font-semibold">{{ props.materi.pertemuan_ke }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Guru</p>
                    <p class="font-semibold">{{ props.guru.nama || '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Mata Pelajaran</p>
                    <p class="font-semibold">{{ props.jadwal.nama_mapel || '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Kelas</p>
                    <p class="font-semibold">{{ props.jadwal.kelas || '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Semester</p>
                    <p class="font-semibold">{{ props.materi.semester_ajaran.semester || '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Tahun Ajaran</p>
                    <p class="font-semibold">{{ props.materi.semester_ajaran.tahun_ajaran || '-' }}</p>
                </div>

                <!-- Deskripsi Materi -->
                <div class="col-span-1 md:col-span-2">
                    <p class="text-sm text-gray-400">Deskripsi</p>
                    <p class="font-semibold whitespace-pre-line">{{ props.materi.deskripsi || '-' }}</p>
                </div>
            </div>

            <!-- File Materi -->
            <div class="mt-4 flex flex-col gap-3 md:max-w-3xl">
                <p class="text-sm text-gray-400">File Materi</p>
                <div
                    v-if="props.materi?.file_materi"
                    class="flex items-center gap-4 rounded-xl border border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100 p-4 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:from-gray-800 dark:to-gray-900"
                >
                    <!-- Icon File -->
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500 text-white shadow">
                        <File class="h-6 w-6" />
                    </div>

                    <!-- Nama File -->
                    <div class="flex-1 truncate">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Materi File</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                            {{ props.materi.file_materi.split('/').pop() }}
                        </p>
                    </div>

                    <!-- Action -->
                    <a
                        :href="props.materi.file_materi"
                        target="_blank"
                        class="inline-flex items-center gap-1 rounded-lg border border-blue-500 px-3 py-1.5 text-sm font-medium text-blue-600 transition hover:bg-blue-500 hover:text-white"
                    >
                        <FileDown class="h-4 w-4" />
                        Download
                    </a>
                </div>
                <div v-else class="text-sm text-gray-500 italic">Belum ada file materi.</div>
            </div>

            <!-- Link Materi -->
            <div class="mt-4 flex flex-col gap-3 md:max-w-3xl">
                <p class="text-sm text-gray-400">Link Materi</p>
                <div
                    v-if="props.materi?.link_materi"
                    class="flex items-center gap-4 rounded-xl border border-gray-200 bg-gradient-to-r from-green-50 to-green-100 p-4 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:from-gray-800 dark:to-gray-900"
                >
                    <!-- Icon Link -->
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-500 text-white shadow">
                        <LinkIcon class="h-6 w-6" />
                    </div>

                    <!-- Info -->
                    <div class="flex-1 truncate">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Link Materi</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ props.materi.link_materi }}</p>
                    </div>

                    <!-- Action -->
                    <a
                        :href="props.materi.link_materi"
                        target="_blank"
                        class="inline-flex items-center gap-1 rounded-lg border border-green-500 px-3 py-1.5 text-sm font-medium text-green-600 transition hover:bg-green-500 hover:text-white"
                    >
                        <ExternalLink class="h-4 w-4" />
                        Buka
                    </a>
                </div>
                <div v-else class="text-sm text-gray-500 italic">Belum ada link materi.</div>
            </div>
        </div>
    </AppLayout>
</template>
