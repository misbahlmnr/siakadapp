<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import { Calendar, CheckCircle, FileText, Users } from 'lucide-vue-next';

defineProps<{
    userRole: string;
    guruId: number;
    jadwalHariIni: {
        id: number;
        mata_pelajaran: string;
        kelas: string;
        jam_mulai: string;
        jam_selesai: string;
    }[];
    statistik: {
        jumlahSiswa: number;
        materiMingguIni: number;
        nilaiDiinput: number;
    };
}>();

dayjs.locale('id');

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: route('guru.dashboard') }];
</script>

<template>
    <Head title="Dashboard Guru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <!-- Statistik Singkat -->
            <div class="grid gap-4 md:grid-cols-3">
                <div class="flex items-center gap-4 rounded-xl border p-4 shadow-sm">
                    <Users class="h-8 w-8 text-blue-500" />
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Siswa</p>
                        <p class="text-2xl font-bold">{{ statistik.jumlahSiswa }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-xl border p-4 shadow-sm">
                    <FileText class="h-8 w-8 text-green-500" />
                    <div>
                        <p class="text-sm text-gray-500">Materi Minggu Ini</p>
                        <p class="text-2xl font-bold">{{ statistik.materiMingguIni }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-xl border p-4 shadow-sm">
                    <CheckCircle class="h-8 w-8 text-purple-500" />
                    <div>
                        <p class="text-sm text-gray-500">Nilai Diinput</p>
                        <p class="text-2xl font-bold">{{ statistik.nilaiDiinput }}</p>
                    </div>
                </div>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="rounded-xl border shadow-sm">
                <div class="flex items-center justify-between border-b p-4">
                    <h2 class="flex items-center gap-2 text-lg font-semibold">
                        <Calendar class="h-5 w-5" />
                        Jadwal Hari Ini ({{ dayjs().format('dddd, DD MMMM YYYY') }})
                    </h2>
                </div>
                <div>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2">Jam</th>
                                <th class="px-4 py-2">Mata Pelajaran</th>
                                <th class="px-4 py-2">Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="jadwal in jadwalHariIni" :key="jadwal.id" class="border-t">
                                <td class="px-4 py-2">{{ jadwal.jam_mulai }} - {{ jadwal.jam_selesai }}</td>
                                <td class="px-4 py-2">{{ jadwal.mata_pelajaran }}</td>
                                <td class="px-4 py-2">{{ jadwal.kelas }}</td>
                            </tr>
                            <tr v-if="jadwalHariIni.length === 0">
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada jadwal hari ini.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
