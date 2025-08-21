<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

defineProps<{
    jadwal: any;
    materi: Array<any>;
    tugas: Array<any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
    { title: 'Detail Jadwal', href: '#' },
];
</script>

<template>
    <Head title="Detail Jadwal Mengajar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 px-10 py-6">
            <!-- Info Jadwal -->
            <div class="rounded-lg border bg-white p-6 shadow-md dark:border-gray-700 dark:bg-[#121212]">
                <h1 class="mb-4 text-2xl font-bold">Detail Jadwal Mengajar</h1>
                <div class="flex flex-col text-gray-700 md:grid-cols-3 dark:text-gray-200">
                    <div><span class="font-semibold">Mata Pelajaran:</span> {{ jadwal.mata_pelajaran.nama_mapel }}</div>
                    <div><span class="font-semibold">Kelas:</span> {{ jadwal.kelas.nama_kelas }}</div>
                    <div>
                        <span class="font-semibold">Hari & Jam:</span> {{ jadwal.hari }} ({{ jadwal.jam_mulai.slice(0, 5) }} -
                        {{ jadwal.jam_selesai.slice(0, 5) }})
                    </div>
                </div>
            </div>

            <!-- Materi -->
            <div class="rounded-lg border bg-white p-6 shadow-md dark:border-gray-700 dark:bg-[#121212]">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="mb-4 text-xl font-semibold">📘 Materi</h2>
                    <Button
                        type="button"
                        @click="router.visit(route('guru.jadwal-mengajar.materi.create', { jadwal_id: jadwal.id }))"
                        class="bg-blue-600 text-white hover:bg-blue-600/90"
                        >Tambah Materi</Button
                    >
                </div>
                <div v-if="materi.length">
                    <table class="w-full border-collapse text-left">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="border p-2">Pertemuan</th>
                                <th class="border p-2">Judul</th>
                                <th class="border p-2">File</th>
                                <th class="border p-2">Link</th>
                                <th class="border p-2">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="m in materi" :key="m.id" class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="border p-2">Pertemuan {{ m.pertemuan_ke }}</td>
                                <td class="border p-2">{{ m.judul_materi }}</td>
                                <td class="border p-2">
                                    <a v-if="m.file_materi" :href="`/storage/${m.file_materi}`" target="_blank" class="text-blue-400 hover:underline"
                                        >Download</a
                                    >
                                    <span v-else class="text-gray-400 italic">Tidak ada file</span>
                                </td>
                                <td class="border p-2">
                                    <a v-if="m.link_materi" :href="m.link_materi" target="_blank" class="text-blue-400 hover:underline">Buka Link</a>
                                    <span v-else class="text-gray-400 italic">Tidak ada link</span>
                                </td>
                                <td class="border p-2">{{ new Date(m.created_at).toLocaleDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-gray-400 italic">Belum ada materi yang diupload.</div>
            </div>

            <!-- Tugas -->
            <div class="rounded-lg border bg-white p-6 shadow-md dark:border-gray-700 dark:bg-[#121212]">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="mb-4 text-xl font-semibold">📝 Tugas / Evaluasi</h2>
                    <Button type="button" class="bg-blue-600 text-white hover:bg-blue-600/90">Tambah Tugas</Button>
                </div>
                <div v-if="tugas.length">
                    <table class="w-full border-collapse text-left">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="border p-2">#</th>
                                <th class="border p-2">Judul</th>
                                <th class="border p-2">Tanggal Mulai</th>
                                <th class="border p-2">Deadline</th>
                                <th class="border p-2">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(t, index) in tugas" :key="t.id" class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="border p-2">{{ index + 1 }}</td>
                                <td class="border p-2">{{ t.judul }}</td>
                                <td class="border p-2">{{ new Date(t.tanggal_mulai).toLocaleDateString() }}</td>
                                <td class="border p-2">{{ t.tanggal_selesai ? new Date(t.tanggal_selesai).toLocaleDateString() : '-' }}</td>
                                <td class="border p-2">
                                    <a v-if="t.file_soal" :href="`/storage/${t.file_soal}`" target="_blank" class="text-blue-600 hover:underline"
                                        >Download</a
                                    >
                                    <span v-else class="text-gray-400 italic">Tidak ada file</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-gray-400 italic">Belum ada tugas atau evaluasi yang dibuat.</div>
            </div>
        </div>
    </AppLayout>
</template>
