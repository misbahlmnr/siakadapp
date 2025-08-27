<template>
    <AppLayout>
        <Head title="Absensi Siswa" />

        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Absensi Siswa</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola kehadiran siswa dengan mudah</p>
                </div>
                <Button class="bg-blue-600 text-white hover:bg-blue-600/90" @click="router.visit(route('guru.absensi.create'))">
                    <Plus class="mr-2 h-4 w-4" />
                    Tambah Absensi
                </Button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                            <Users class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Hadir</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.hadir }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                            <AlertCircle class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Izin</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.izin }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                            <Heart class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sakit</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.sakit }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-red-100 p-3 dark:bg-red-900">
                            <XCircle class="h-6 w-6 text-red-600 dark:text-red-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Alpha</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.alpha }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Filter Data</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <!-- Kelas Filter -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                        <select
                            v-model="filters.kelas"
                            @change="applyFilters"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Semua Kelas</option>
                            <option v-for="kelas in kelasOptions" :key="kelas" :value="kelas">{{ kelas }}</option>
                        </select>
                    </div>

                    <!-- Mata Pelajaran Filter -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                        <select
                            v-model="filters.mapel"
                            @change="applyFilters"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Semua Mapel</option>
                            <option v-for="mapel in mapelOptions" :key="mapel" :value="mapel">{{ mapel }}</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Semua Status</option>
                            <option v-for="status in statusOptions" :key="status" :value="status">{{ status }}</option>
                        </select>
                    </div>

                    <!-- Pertemuan Filter -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Pertemuan</label>
                        <select
                            v-model="filters.pertemuan"
                            @change="applyFilters"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Semua Pertemuan</option>
                            <option v-for="n in 20" :key="n" :value="n">Pertemuan {{ n }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Absensi</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Kelas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Mata Pelajaran
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Pertemuan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            <tr v-for="(absensi, index) in filteredAbsensi" :key="absensi.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-white">{{ index + 1 }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-white">{{ absensi.siswa }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-white">{{ absensi.kelas }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-white">{{ absensi.mata_pelajaran }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-white">{{ absensi.pertemuan_ke }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-white">{{ absensi.tanggal }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusBadgeClass(absensi.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                                        {{ absensi.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <Button variant="outline" size="sm" @click="viewAbsensi(absensi.id)">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" size="sm" @click="editAbsensi(absensi.id)">
                                            <Edit class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" size="sm" @click="deleteAbsensi(absensi.id)">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="filteredAbsensi.length === 0" class="p-8 text-center">
                    <div class="text-gray-400 dark:text-gray-500">
                        <ClipboardList class="mx-auto mb-4 h-12 w-12" />
                        <p>Tidak ada data absensi</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { AlertCircle, ClipboardList, Edit, Eye, Heart, Plus, Trash2, Users, XCircle } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

// Props dari Laravel Controller
const props = defineProps<{
    kelasOptions: string[];
    mapelOptions: string[];
    absensiData?: any[];
}>();

// State untuk filter
const filters = ref({
    kelas: '',
    mapel: '',
    status: '',
    pertemuan: '',
});

const statusOptions = ['hadir', 'sakit', 'ijin', 'alfa'];
const absensi = ref<any[]>([]);

// Fetch data absensi
const fetchAbsensiData = async () => {
    try {
        const response = await fetch(route('guru.absensi.data'), {
            method: 'GET',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const data = await response.json();
        absensi.value = data.data;
    } catch (error) {
        console.error('Error fetching absensi data:', error);
    }
};

// Filter data berdasarkan kriteria
const filteredAbsensi = computed(() => {
    return absensi.value.filter((item) => {
        const matchesKelas = !filters.value.kelas || item.kelas === filters.value.kelas;
        const matchesMapel = !filters.value.mapel || item.mata_pelajaran === filters.value.mapel;
        const matchesStatus = !filters.value.status || item.status.toLowerCase() === filters.value.status;
        const matchesPertemuan = !filters.value.pertemuan || item.pertemuan_ke == filters.value.pertemuan;

        return matchesKelas && matchesMapel && matchesStatus && matchesPertemuan;
    });
});

// Hitung statistik
const stats = computed(() => {
    return {
        hadir: absensi.value.filter((a) => a.status.toLowerCase() === 'hadir').length,
        izin: absensi.value.filter((a) => a.status.toLowerCase() === 'izin').length,
        sakit: absensi.value.filter((a) => a.status.toLowerCase() === 'sakit').length,
        alpha: absensi.value.filter((a) => a.status.toLowerCase() === 'alfa').length,
    };
});

const applyFilters = () => {
    // Filter akan otomatis diterapkan melalui computed property
};

const getStatusBadgeClass = (status: string) => {
    const statusLower = status.toLowerCase();
    const classes: Record<string, string> = {
        hadir: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        izin: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        sakit: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        alfa: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[statusLower] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const viewAbsensi = (id: number) => {
    router.visit(route('guru.absensi.show', { id }));
};

const editAbsensi = (id: number) => {
    router.visit(route('guru.absensi.edit', { id }));
};

const deleteAbsensi = async (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus absensi ini?')) {
        try {
            await fetch(route('guru.absensi.destroy', { id }), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    Accept: 'application/json',
                },
            });
            await fetchAbsensiData(); // Refresh data
        } catch (error) {
            console.error('Error deleting absensi:', error);
        }
    }
};

onMounted(() => {
    fetchAbsensiData();
});
</script>
