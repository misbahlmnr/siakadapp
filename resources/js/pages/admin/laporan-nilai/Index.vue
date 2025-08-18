<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Kelas, MatPel } from '@/types';
import { Head } from '@inertiajs/vue3';
import $ from 'jquery';
import { ChevronDown, FileDown } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

const props = defineProps<{
    kelas: Kelas[];
    matpel: MatPel[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Laporan Nilai', href: route('admin.laporan-nilai.index') },
];

// state filter
const kelasId = ref('');
const mapelId = ref('');
const semester = ref('');
const tahunAjaran = ref('');

// label untuk dropdown
const selectedKelasLabel = ref('Pilih Kelas');
const selectedMapelLabel = ref('Pilih Mapel');
const selectedSemesterLabel = ref('Semester');

onMounted(() => {
    useDataTable('#laporan-nilai-table', {
        ajax: {
            url: route('admin.laporan-nilai.data'),
            data: function (d: any) {
                d.kelas_id = kelasId.value;
                d.mapel_id = mapelId.value;
                d.semester = semester.value;
                d.tahun_ajaran = tahunAjaran.value;
            },
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'nama_siswa', name: 'nama_siswa' },
            { data: 'kelas', name: 'kelas' },
            { data: 'mapel', name: 'mapel' },
            { data: 'guru', name: 'guru' },
            { data: 'jenis', name: 'jenis' },
            { data: 'nilai', name: 'nilai' },
            { data: 'semester', name: 'semester' },
            { data: 'tahun_ajarannya', name: 'tahun_ajarannya' },
        ],
    });
});

watch([kelasId, mapelId, semester, tahunAjaran], () => {
    $('#laporan-nilai-table').DataTable().ajax.reload();
});

const exportExcel = () => {
    const url = route('admin.laporan-nilai.export-excel', {
        kelas_id: kelasId.value,
        mapel_id: mapelId.value,
        semester: semester.value,
        tahun_ajaran: tahunAjaran.value,
    });
    window.open(url, '_blank');
};

const exportPdf = () => {
    const url = route('admin.laporan-nilai.export-pdf', {
        kelas_id: kelasId.value,
        mapel_id: mapelId.value,
        semester: semester.value,
        tahun_ajaran: tahunAjaran.value,
    });
    window.open(url, '_blank');
};
</script>

<template>
    <Head title="Laporan Nilai" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Laporan Nilai</h1>
                <div class="flex gap-2">
                    <Button
                        class="flex items-center gap-1 rounded bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-600/90"
                        @click="exportExcel"
                        ><FileDown :size="18" /> Export Excel
                    </Button>
                    <Button class="flex items-center gap-1 rounded bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-500/90" @click="exportPdf"
                        ><FileDown :size="18" /> Export PDF
                    </Button>
                </div>
            </div>

            <!-- Filter -->
            <div class="grid grid-cols-4 gap-4">
                <!-- Dropdown Kelas -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full justify-between rounded border px-4 py-2 text-sm" type="button">
                            <span>{{ selectedKelasLabel }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            @click="
                                kelasId = '';
                                selectedKelasLabel = 'Semua Kelas';
                            "
                        >
                            Semua Kelas
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-for="kelas in props.kelas"
                            :key="kelas.id"
                            @click="
                                kelasId = kelas.id;
                                selectedKelasLabel = kelas.nama_kelas;
                            "
                        >
                            {{ kelas.nama_kelas }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Dropdown Mapel -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full justify-between rounded border px-4 py-2 text-sm" type="button">
                            <span>{{ selectedMapelLabel }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            @click="
                                mapelId = '';
                                selectedMapelLabel = 'Semua Mapel';
                            "
                        >
                            Semua Mapel
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-for="m in props.matpel"
                            :key="m.id"
                            @click="
                                mapelId = m.id;
                                selectedMapelLabel = m.nama_mapel;
                            "
                        >
                            {{ m.nama_mapel }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Dropdown Semester -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full justify-between rounded border px-4 py-2 text-sm" type="button">
                            <span>{{ selectedSemesterLabel }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <DropdownMenuItem
                            @click="
                                semester = '';
                                selectedSemesterLabel = 'Semua';
                            "
                        >
                            Semua
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            @click="
                                semester = 'ganjil';
                                selectedSemesterLabel = 'Ganjil';
                            "
                        >
                            Ganjil
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            @click="
                                semester = 'genap';
                                selectedSemesterLabel = 'Genap';
                            "
                        >
                            Genap
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Input Tahun Ajaran -->
                <input v-model="tahunAjaran" type="text" placeholder="Tahun Ajaran (misal: 2024/2025)" class="rounded border p-2 text-sm" />
            </div>

            <!-- Table -->
            <div>
                <table id="laporan-nilai-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Guru</th>
                            <th>Jenis</th>
                            <th>Nilai</th>
                            <th>Semester</th>
                            <th>Tahun Ajaran</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
