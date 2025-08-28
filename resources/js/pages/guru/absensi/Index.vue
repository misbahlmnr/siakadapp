<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ChevronDown, Plus } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

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

let table: any;

// Initialize DataTable
onMounted(() => {
    table = useDataTable('#absensi-table', {
        ajax: {
            url: route('guru.absensi.data'),
            data: function (d) {
                d.kelas = filters.value.kelas || '';
                d.mapel = filters.value.mapel || '';
                d.status = filters.value.status || '';
                d.pertemuan = filters.value.pertemuan || '';
            },
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'siswa', name: 'siswa' },
            { data: 'kelas', name: 'kelas' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'pertemuan_ke', name: 'pertemuan_ke' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'status', name: 'status' },
        ],
        editRoute: 'guru.absensi.edit',
        deleteRoute: 'guru.absensi.destroy',
        detailRoute: 'guru.absensi.show',
    });
});

const applyFilters = () => {
    if (table) {
        table.ajax.reload();
    }
};
</script>

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

            <!-- Filters -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- Kelas Filter -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ filters.kelas || 'Semua Kelas' }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            @click="
                                filters.kelas = '';
                                applyFilters();
                            "
                            >Semua Kelas</DropdownMenuItem
                        >
                        <DropdownMenuItem
                            v-for="kelas in props.kelasOptions"
                            :key="kelas"
                            @click="
                                filters.kelas = kelas;
                                applyFilters();
                            "
                            >{{ kelas }}</DropdownMenuItem
                        >
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Mata Pelajaran Filter -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ filters.mapel || 'Semua Mapel' }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            @click="
                                filters.mapel = '';
                                applyFilters();
                            "
                            >Semua Mapel</DropdownMenuItem
                        >
                        <DropdownMenuItem
                            v-for="mapel in props.mapelOptions"
                            :key="mapel"
                            @click="
                                filters.mapel = mapel;
                                applyFilters();
                            "
                            >{{ mapel }}</DropdownMenuItem
                        >
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Status Filter -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ filters.status || 'Semua Status' }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            @click="
                                filters.status = '';
                                applyFilters();
                            "
                            >Semua Status</DropdownMenuItem
                        >
                        <DropdownMenuItem
                            v-for="status in ['hadir', 'sakit', 'ijin', 'alfa']"
                            :key="status"
                            @click="
                                filters.status = status;
                                applyFilters();
                            "
                            >{{ status }}</DropdownMenuItem
                        >
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Pertemuan Filter -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ filters.pertemuan || 'Semua Pertemuan' }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            @click="
                                filters.pertemuan = '';
                                applyFilters();
                            "
                            >Semua Pertemuan</DropdownMenuItem
                        >
                        <DropdownMenuItem
                            v-for="n in 20"
                            :key="n"
                            @click="
                                filters.pertemuan = n.toString();
                                applyFilters();
                            "
                            >Pertemuan {{ n }}</DropdownMenuItem
                        >
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <!-- Data Table -->
            <div>
                <table id="absensi-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
