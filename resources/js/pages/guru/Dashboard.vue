<script setup lang="ts">
import AdaptiveChart from '@/components/AdaptiveChart.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { AlertCircle, BookOpen, Calendar, CheckCircle2, ChevronDown, ClipboardList, Clock, Eye, FileText, TrendingUp, Users } from 'lucide-vue-next';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: route('guru.dashboard') }];

const props = defineProps<{
    totalKelas: number;
    totalMatpel: number;
    totalMateri: number;
    totalEvaluasi: number;
    adaptiveProgress: {
        belum_dibaca: number;
        dibaca: number;
        selesai: number;
    };
    todaySchedule: {
        hari: string;
        jam_mulai: string;
        jam_selesai: string;
        nama_kelas: string;
        nama_mapel: string;
    }[];
    jadwalMengajarList: { id: number; nama_jadwal: string }[];
}>();

const toast = useToast();

const showMateriDialog = ref(false);
const showEvaluasiDialog = ref(false);

const jadwalPelajaranId = ref<number | null>(null);
const selectedJadwalMengajarLabel = ref('Pilih Jadwal Mengajar');

const handleCreateMateri = () => {
    if (!jadwalPelajaranId.value) {
        toast.error('Silakan pilih jadwal mengajar terlebih dahulu.');
        return;
    }

    router.visit(route('guru.jadwal-mengajar.materi.create', { jadwal_id: jadwalPelajaranId.value }));
};

const handleEvaluasiPembelajaran = () => {
    if (!jadwalPelajaranId.value) {
        toast.error('Silakan pilih jadwal mengajar terlebih dahulu.');
        return;
    }

    router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.create', { jadwal_id: jadwalPelajaranId.value }));
};
</script>

<template>
    <Head title="Dashboard Guru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 bg-gray-50 p-6 dark:bg-[#0A0A0A]">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Guru</h1>
                    <p class="text-gray-600 dark:text-gray-400">Selamat datang! Berikut ringkasan aktivitas mengajar Anda.</p>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{
                        new Date().toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                        })
                    }}
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Buat Materi -->
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-blue-600"
                    @click="showMateriDialog = true"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-blue-500 p-2">
                            <FileText class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">
                                Buat Materi Baru
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Upload materi pembelajaran baru</p>
                        </div>
                    </div>
                </div>

                <!-- Buat Evaluasi -->
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-green-600"
                    @click="showEvaluasiDialog = true"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-green-500 p-2">
                            <ClipboardList class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-green-600 dark:text-white dark:group-hover:text-green-400">
                                Buat Evaluasi
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Buat tugas atau ujian baru</p>
                        </div>
                    </div>
                </div>

                <!-- Jadwal -->
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-purple-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-purple-600"
                    @click="router.visit(route('guru.jadwal-mengajar.index'))"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-500 p-2">
                            <Calendar class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 dark:text-white dark:group-hover:text-purple-400">
                                Lihat Jadwal
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Lihat jadwal mengajar lengkap</p>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-orange-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-orange-600"
                    @click="router.visit(route('guru.rekomendasi-materi.index'))"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-orange-500 p-2">
                            <TrendingUp class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-orange-600 dark:text-white dark:group-hover:text-orange-400">
                                Rekomendasi
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kelola rekomendasi materi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Buat Materi -->
            <Dialog v-model:open="showMateriDialog">
                <DialogContent class="sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle>Buat Materi Baru</DialogTitle>
                        <DialogDescription>Silahkan pilih jadwal dibawah untuk pergi ke halaman buat materi</DialogDescription>
                    </DialogHeader>

                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedJadwalMengajarLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="jm in props.jadwalMengajarList"
                                :key="jm.id"
                                @click="
                                    jadwalPelajaranId = jm.id;
                                    selectedJadwalMengajarLabel = jm.nama_jadwal;
                                "
                            >
                                {{ jm.nama_jadwal }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <DialogFooter>
                        <DialogClose as-child>
                            <Button type="button" variant="outline">Batal</Button>
                        </DialogClose>
                        <Button type="button" class="rounded-lg bg-blue-600 text-white hover:bg-blue-600/90" @click="handleCreateMateri"
                            >Upload Materi</Button
                        >
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Modal Buat Evaluasi -->
            <Dialog v-model:open="showEvaluasiDialog">
                <DialogContent class="sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle>Buat Evaluasi</DialogTitle>
                        <DialogDescription>Tambahkan tugas, kuis, atau ujian baru.</DialogDescription>
                    </DialogHeader>

                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedJadwalMengajarLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="jm in props.jadwalMengajarList"
                                :key="jm.id"
                                @click="
                                    jadwalPelajaranId = jm.id;
                                    selectedJadwalMengajarLabel = jm.nama_jadwal;
                                "
                            >
                                {{ jm.nama_jadwal }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <DialogFooter>
                        <DialogClose as-child>
                            <Button type="button" variant="outline">Batal</Button>
                        </DialogClose>
                        <Button type="button" class="rounded-lg bg-green-600 text-white hover:bg-green-600/90" @click="handleEvaluasiPembelajaran"
                            >Buat Tugas</Button
                        >
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Stats Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                            <Users class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas Diampu</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalKelas }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                            <BookOpen class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalMatpel }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/30">
                            <FileText class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Materi Diupload</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalMateri }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/30">
                            <ClipboardList class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tugas/Ujian Dibuat</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalEvaluasi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Jadwal Mengajar Hari Ini -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Mengajar Hari Ini</h2>
                        <Calendar class="h-5 w-5 text-gray-400" />
                    </div>

                    <div v-if="todaySchedule.length > 0" class="space-y-3">
                        <div
                            v-for="(item, index) in todaySchedule"
                            :key="index"
                            class="flex items-center justify-between rounded-lg bg-blue-50 p-4 shadow-sm dark:bg-blue-900/20"
                        >
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ item.nama_mapel }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ item.nama_kelas }}</div>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <Clock class="h-4 w-4" />
                                {{ item.jam_mulai }} - {{ item.jam_selesai }}
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
                        <Calendar class="mx-auto mb-2 h-12 w-12 opacity-50" />
                        <p>Tidak ada jadwal mengajar hari ini</p>
                    </div>
                </div>

                <!-- Progress Adaptive Learning -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Progress Adaptive Learning</h2>
                        <TrendingUp class="h-5 w-5 text-gray-400" />
                    </div>

                    <div class="mb-4 grid gap-4">
                        <div class="flex items-center justify-between rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                            <div class="flex items-center gap-2">
                                <AlertCircle class="h-4 w-4 text-yellow-600 dark:text-yellow-400" />
                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Belum Dibaca</span>
                            </div>
                            <span class="font-bold text-yellow-800 dark:text-yellow-200">{{ adaptiveProgress.belum_dibaca }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                            <div class="flex items-center gap-2">
                                <Eye class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Dibaca</span>
                            </div>
                            <span class="font-bold text-blue-800 dark:text-blue-200">{{ adaptiveProgress.dibaca }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="h-4 w-4 text-green-600 dark:text-green-400" />
                                <span class="text-sm font-medium text-green-800 dark:text-green-200">Selesai</span>
                            </div>
                            <span class="font-bold text-green-800 dark:text-green-200">{{ adaptiveProgress.selesai }}</span>
                        </div>
                    </div>

                    <!-- Grafik Progress -->
                    <div class="relative h-64 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <AdaptiveChart
                            :labels="['Belum Dibaca', 'Dibaca', 'Selesai']"
                            :data="[adaptiveProgress.belum_dibaca, adaptiveProgress.dibaca, adaptiveProgress.selesai]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.group:hover h3 {
    transition: color 0.2s ease;
}
</style>
