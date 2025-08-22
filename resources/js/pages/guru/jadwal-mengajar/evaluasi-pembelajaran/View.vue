<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ExternalLink, File, FileDown } from 'lucide-vue-next';

const props = defineProps<{
    jadwal_id: number;
    evaluasi: {
        id: number;
        jadwal_id: number;
        judul: string;
        deskripsi: string | null;
        jenis: string;
        semester: string;
        tahun_ajaran: string;
        waktu_mulai: string;
        waktu_selesai: string;
        file_soal: string | null;
        link_soal: string | null;
    };
}>();
</script>

<template>
    <Head :title="`Detail Evaluasi: ${props.evaluasi.judul}`" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
            { title: 'Evaluasi Pembelajaran', href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: props.jadwal_id }) },
            { title: props.evaluasi.judul, href: '#' },
        ]"
    >
        <div class="flex flex-col gap-6 px-8 py-6">
            <h1 class="text-2xl font-bold">Detail Evaluasi Pembelajaran</h1>

            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-2xl md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Judul</p>
                    <p class="font-semibold">{{ props.evaluasi.judul }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Jenis</p>
                    <p class="font-semibold capitalize">{{ props.evaluasi.jenis }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Semester</p>
                    <p class="font-semibold">{{ props.evaluasi.semester || '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Tahun Ajaran</p>
                    <p class="font-semibold">{{ props.evaluasi.tahun_ajaran || '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-400">Waktu</p>
                    <p class="font-semibold">
                        {{ new Date(props.evaluasi.waktu_mulai).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        –
                        {{ new Date(props.evaluasi.waktu_selesai).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                    </p>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <p class="text-sm text-gray-400">Deskripsi</p>
                    <p class="font-semibold whitespace-pre-line">{{ props.evaluasi.deskripsi || '-' }}</p>
                </div>

                <!-- File Soal -->
                <div class="col-span-1 flex flex-col gap-3 md:col-span-2">
                    <p class="text-sm text-gray-400">File Soal</p>
                    <div
                        v-if="props.evaluasi.file_soal"
                        class="flex items-center gap-4 rounded-xl border border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100 p-4 shadow-sm dark:border-gray-700 dark:from-gray-800 dark:to-gray-900"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500 text-white shadow">
                            <File class="h-6 w-6" />
                        </div>
                        <div class="flex-1 truncate">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200">File Soal</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                {{ props.evaluasi.file_soal.split('/').pop() }}
                            </p>
                        </div>
                        <a
                            :href="`/storage/${props.evaluasi.file_soal}`"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded-lg border border-blue-500 px-3 py-1.5 text-sm font-medium text-blue-600 transition hover:bg-blue-500 hover:text-white"
                        >
                            <FileDown class="h-4 w-4" />
                            Download
                        </a>
                    </div>
                    <div v-else class="text-sm text-gray-500 italic">Belum ada file soal.</div>
                </div>

                <!-- Link Soal -->
                <div class="col-span-1 flex flex-col gap-3 md:col-span-2">
                    <p class="text-sm text-gray-400">Link Soal</p>
                    <div
                        v-if="props.evaluasi.link_soal"
                        class="flex items-center gap-4 rounded-xl border border-gray-200 bg-gradient-to-r from-green-50 to-green-100 p-4 shadow-sm dark:border-gray-700 dark:from-gray-800 dark:to-gray-900"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-500 text-white shadow">
                            <ExternalLink class="h-6 w-6" />
                        </div>
                        <div class="flex-1 truncate">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Link Soal</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ props.evaluasi.link_soal }}</p>
                        </div>
                        <a
                            :href="props.evaluasi.link_soal"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded-lg border border-green-500 px-3 py-1.5 text-sm font-medium text-green-600 transition hover:bg-green-500 hover:text-white"
                        >
                            <ExternalLink class="h-4 w-4" />
                            Buka
                        </a>
                    </div>
                    <div v-else class="text-sm text-gray-500 italic">Belum ada link soal.</div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex gap-4">
                <Button
                    variant="outline"
                    @click="router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: props.jadwal_id }))"
                >
                    Kembali
                </Button>
                <Button
                    @click="
                        router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.edit', { jadwal_id: props.jadwal_id, id: props.evaluasi.id }))
                    "
                    class="bg-blue-600 text-white hover:bg-blue-700"
                >
                    Edit Data
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
