<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, BookOpen, ExternalLink, File, FileDown } from 'lucide-vue-next';

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
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button
                        variant="ghost"
                        size="icon"
                        @click="router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: props.jadwal_id }))"
                        class="h-8 w-8"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Evaluasi Pembelajaran</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap evaluasi pembelajaran</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        @click="
                            router.visit(
                                route('guru.jadwal-mengajar.evaluasi-pembelajaran.edit', { jadwal_id: props.jadwal_id, id: props.evaluasi.id }),
                            )
                        "
                    >
                        Edit Data
                    </Button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Info Evaluasi -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <BookOpen class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        Informasi Evaluasi
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Judul</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.evaluasi.judul }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jenis</p>
                            <p class="text-gray-900 capitalize dark:text-gray-200">{{ props.evaluasi.jenis }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Semester</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.evaluasi.semester_ajaran.semester || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun Ajaran</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.evaluasi.semester_ajaran.tahun_ajaran || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Waktu</p>
                            <p class="text-gray-900 dark:text-gray-200">
                                {{ new Date(props.evaluasi.waktu_mulai).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                                –
                                {{ new Date(props.evaluasi.waktu_selesai).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi & Lampiran -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <File class="h-5 w-5 text-green-600 dark:text-green-400" />
                        Deskripsi & Lampiran
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Deskripsi</p>
                            <p class="whitespace-pre-line text-gray-900 dark:text-gray-200">{{ props.evaluasi.deskripsi || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">File Soal</p>
                            <div v-if="props.evaluasi.file_soal" class="mt-2 flex items-center gap-3">
                                <a
                                    :href="`/storage/${props.evaluasi.file_soal}`"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <FileDown class="h-4 w-4" />
                                    Download Soal
                                </a>
                            </div>
                            <p v-else class="text-sm text-gray-500 italic">Belum ada file soal.</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Link Soal</p>
                            <div v-if="props.evaluasi.link_soal" class="mt-2 flex items-center gap-3">
                                <a
                                    :href="props.evaluasi.link_soal"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800"
                                >
                                    <ExternalLink class="h-4 w-4" />
                                    Buka Link
                                </a>
                            </div>
                            <p v-else class="text-sm text-gray-500 italic">Belum ada link soal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <hr class="my-8 border-t border-gray-300 dark:border-gray-700" />

            <!-- Siswa yang Mengumpulkan -->
            <div>
                <h2 class="mb-3 text-2xl font-bold text-gray-800 dark:text-gray-200">Siswa yang Sudah Mengumpulkan</h2>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Berikut daftar siswa yang sudah mengumpulkan tugas evaluasi.</p>

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
                            <div>
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
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <ExternalLink class="h-4 w-4" />
                                    Link Jawaban
                                </a>
                                <a
                                    v-if="pengumpulan.file_jawaban"
                                    :href="pengumpulan.file_jawaban"
                                    target="_blank"
                                    @click.stop
                                    class="inline-flex items-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800"
                                >
                                    <FileDown class="h-4 w-4" />
                                    File Jawaban
                                </a>
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
