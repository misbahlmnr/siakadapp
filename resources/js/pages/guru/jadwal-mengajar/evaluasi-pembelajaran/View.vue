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
        semester_ajaran_id: number;
        judul: string;
        deskripsi: string | null;
        jenis: string;
        waktu_mulai: string;
        waktu_selesai: string;
        file_soal: string | null;
        link_soal: string | null;
        semester_ajaran: any;
    };
    pengumpulanTugasList: any[];
}>();
console.log(props.pengumpulanTugasList);
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
                    <p class="font-semibold">{{ props.evaluasi.semester_ajaran.semester || '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Tahun Ajaran</p>
                    <p class="font-semibold">{{ props.evaluasi.semester_ajaran.tahun_ajaran || '-' }}</p>
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

            <div>
                <h1 class="mt-8 mb-3 text-2xl font-bold text-gray-800 dark:text-gray-200">Siswa yang Sudah Mengumpulkan</h1>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    Berikut adalah daftar siswa yang telah mengumpulkan tugas evaluasi pembelajaran.
                </p>

                <div class="mt-4 space-y-4 md:max-w-2xl">
                    <div
                        v-for="pengumpulan in props.pengumpulanTugasList"
                        :key="pengumpulan.id"
                        class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-500"
                        @click="
                            router.visit(
                                route('guru.jadwal-mengajar.evaluasi-pembelajaran.pengumpulan-tugas.show', {
                                    jadwal_id: props.jadwal_id,
                                    evaluasi_id: props.evaluasi.id,
                                    pengumpulan_id: pengumpulan.id,
                                }),
                            )
                        "
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3
                                    class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 dark:text-gray-200 dark:group-hover:text-blue-400"
                                >
                                    {{ pengumpulan.siswa }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Status: <span class="font-medium text-green-600 dark:text-green-400">Terkumpul</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <a
                                    v-if="pengumpulan.link_jawaban"
                                    :href="pengumpulan.link_jawaban"
                                    target="_blank"
                                    @click.stop
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <ExternalLink class="h-4 w-4" />
                                    Link Jawaban
                                </a>
                                <a
                                    v-if="pengumpulan.file_jawaban"
                                    :href="pengumpulan.file_jawaban"
                                    target="_blank"
                                    @click.stop
                                    class="inline-flex items-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-sm font-medium text-green-700 transition hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800"
                                >
                                    <FileDown class="h-4 w-4" />
                                    File Jawaban
                                </a>
                                <div class="text-xs text-gray-400 dark:text-gray-500">Klik untuk detail</div>
                            </div>
                        </div>
                    </div>
                    <div v-if="props.pengumpulanTugasList.length === 0" class="py-8 text-center">
                        <div class="text-gray-400 dark:text-gray-500">
                            <File class="mx-auto mb-3 h-12 w-12 opacity-50" />
                            <p class="text-sm">Belum ada pengumpulan tugas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
