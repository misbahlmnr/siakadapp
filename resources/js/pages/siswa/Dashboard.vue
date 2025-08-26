<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Props dari Laravel Controller
defineProps<{
    jadwalHariIni: any[];
    tugasBelum: any[];
    absensi: any[];
    nilaiTerakhir: any[];
    rekomendasi: any[];
}>();
</script>

<template>
    <AppLayout>
        <Head title="Dashboard Siswa" />

        <div class="space-y-6 p-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Selamat Datang, {{ $page.props.auth.user.name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Berikut ringkasan akademik kamu 🚀</p>
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-2xl bg-gradient-to-r from-red-400 to-red-600 p-4 text-white shadow transition hover:scale-105">
                    <p class="text-sm font-medium opacity-90">Tugas Belum</p>
                    <h3 class="text-3xl font-bold">{{ tugasBelum.length }}</h3>
                </div>
                <div class="rounded-2xl bg-gradient-to-r from-blue-400 to-blue-600 p-4 text-white shadow transition hover:scale-105">
                    <p class="text-sm font-medium opacity-90">Jadwal Hari Ini</p>
                    <h3 class="text-3xl font-bold">{{ jadwalHariIni.length }}</h3>
                </div>
                <div class="rounded-2xl bg-gradient-to-r from-green-400 to-green-600 p-4 text-white shadow transition hover:scale-105">
                    <p class="text-sm font-medium opacity-90">Absensi Hadir</p>
                    <h3 class="text-3xl font-bold">{{ absensi.filter((a) => a.status === 'hadir').length }}</h3>
                </div>
                <div class="rounded-2xl bg-gradient-to-r from-yellow-400 to-yellow-600 p-4 text-white shadow transition hover:scale-105">
                    <p class="text-sm font-medium opacity-90">Rekomendasi Materi</p>
                    <h3 class="text-3xl font-bold">{{ rekomendasi.length }}</h3>
                </div>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="rounded-2xl bg-white p-4 shadow transition dark:bg-gray-800">
                <h2 class="mb-3 text-lg font-semibold text-gray-800 dark:text-gray-100">📅 Jadwal Hari Ini</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-center dark:bg-gray-700">
                            <th class="p-2">Mapel</th>
                            <th class="p-2">Guru</th>
                            <th class="p-2">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="j in jadwalHariIni" :key="j.id" class="border-b hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                            <td class="p-2">{{ j.nama_mapel }}</td>
                            <td class="p-2">{{ j.guru }}</td>
                            <td class="p-2">{{ j.jam_mulai }} - {{ j.jam_selesai }}</td>
                        </tr>
                        <tr v-if="!jadwalHariIni.length">
                            <td colspan="3" class="p-3 text-center text-gray-500 dark:text-gray-400">Tidak ada jadwal</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tugas Belum -->
            <div class="rounded-2xl bg-white p-4 shadow transition dark:bg-gray-800">
                <h2 class="mb-3 text-lg font-semibold text-gray-800 dark:text-gray-100">📌 Tugas Belum Dikumpulkan</h2>
                <ul>
                    <li v-for="t in tugasBelum" :key="t.id" class="flex justify-between border-b p-2 dark:border-gray-700">
                        <span class="text-gray-800 dark:text-gray-200"
                            >{{ t.judul }} - <span class="text-gray-500 dark:text-gray-400">{{ t.nama_mapel }}</span></span
                        >
                        <Link :href="route('siswa.tugas.show', t.id)" class="text-blue-600 hover:underline dark:text-blue-400"> Detail </Link>
                    </li>
                    <li v-if="!tugasBelum.length" class="p-2 text-center text-gray-500 dark:text-gray-400">Tidak ada tugas</li>
                </ul>
            </div>

            <!-- Rekomendasi Materi -->
            <div class="rounded-2xl bg-white p-4 shadow transition dark:bg-gray-800">
                <h2 class="mb-3 text-lg font-semibold text-gray-800 dark:text-gray-100">📖 Rekomendasi Materi</h2>
                <ul>
                    <li v-for="m in rekomendasi" :key="m.id" class="flex justify-between border-b p-2 dark:border-gray-700">
                        <span class="text-gray-800 dark:text-gray-200">{{ m.judul_materi }}</span>
                        <Link :href="route('siswa.materi.show', m.id)" class="text-blue-600 hover:underline dark:text-blue-400"> Baca </Link>
                    </li>
                    <li v-if="!rekomendasi.length" class="p-2 text-center text-gray-500 dark:text-gray-400">Belum ada rekomendasi</li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
