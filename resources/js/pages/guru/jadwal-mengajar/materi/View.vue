<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { MateriPelajaran } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { File, FileDown } from 'lucide-vue-next';

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
            <h1 class="text-2xl font-bold">Detail Materi Pembelajaran</h1>

            <!-- Informasi Umum -->
            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-2xl md:grid-cols-2">
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
                <div>
                    <p class="text-sm text-gray-400">Deskripsi</p>
                    <p class="font-semibold whitespace-pre-line">{{ props.materi.deskripsi || '-' }}</p>
                </div>

                <!-- File Materi -->
                <div class="col-span-1 flex flex-col gap-3 md:col-span-2">
                    <Label class="text-sm text-gray-400">File Materi</Label>

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
                <div class="col-span-1 flex flex-col gap-3 md:col-span-2">
                    <Label class="text-sm text-gray-400">Link Materi</Label>

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

            <!-- Tombol Aksi -->
            <div class="mt-6 flex gap-4">
                <Button variant="outline" @click="router.visit(route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.materi.jadwal_id }))">
                    Kembali
                </Button>
                <Button
                    @click="router.visit(route('admin.jadwal-pelajaran.edit', { jadwal_id: props.materi.jadwal_id, materi_id: props.materi.id }))"
                    class="bg-blue-600 text-white hover:bg-blue-700"
                >
                    Edit Data
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
