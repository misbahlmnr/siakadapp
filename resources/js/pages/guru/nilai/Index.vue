<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

defineProps<{
    kelasOptions: any[];
    mapelOptions: any[];
    angkatanOptions: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Nilai Siswa', href: route('guru.nilai.index') },
];

// Filter state
const selectedKelas = ref('Semua Kelas');
const selectedMapel = ref('Semua Mapel');
const selectedJenis = ref('Semua Jenis');
const selectedAngkatan = ref('Semua Angkatan');

const jenisOptions = ['tugas', 'kuis', 'ujian'];

let table: any;

// Helper untuk reload table
function reloadTable() {
    if (table) table.ajax.reload();
}

onMounted(() => {
    table = useDataTable('#data-table', {
        ajax: {
            url: route('guru.nilai.data'),
            data: function (d) {
                d.kelas = selectedKelas.value === 'Semua Kelas' ? '' : selectedKelas.value;
                d.mapel = selectedMapel.value === 'Semua Mapel' ? '' : selectedMapel.value;
                d.jenis = selectedJenis.value === 'Semua Jenis' ? '' : selectedJenis.value;
                d.angkatan = selectedAngkatan.value === 'Semua Angkatan' ? '' : selectedAngkatan.value;
            },
        },
        editRoute: 'guru.nilai.edit',
        deleteRoute: 'guru.nilai.destroy',
        detailRoute: 'guru.nilai.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'siswa', name: 'siswa' },
            { data: 'kelas', name: 'kelas' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'evaluasi', name: 'evaluasi' },
            { data: 'jenis', name: 'jenis' },
            { data: 'nilai', name: 'nilai' },
        ],
    });
});
</script>

<template>
    <Head title="Nilai Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Nilai Siswa</h1>
                <Button class="bg-blue-600 text-white hover:bg-blue-600/90" @click="router.visit(route('guru.nilai.create'))">Tambah Nilai</Button>
            </div>

            <!-- Filter Dropdowns -->
            <div class="mb-4 flex gap-4">
                <!-- Kelas -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ selectedKelas }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            v-for="k in ['Semua Kelas', ...kelasOptions]"
                            :key="k"
                            @click="
                                selectedKelas = k;
                                reloadTable();
                            "
                        >
                            {{ k }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Mata Pelajaran -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ selectedMapel }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            v-for="m in ['Semua Mapel', ...mapelOptions]"
                            :key="m"
                            @click="
                                selectedMapel = m;
                                reloadTable();
                            "
                        >
                            {{ m }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Jenis Evaluasi -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ selectedJenis }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            v-for="j in ['Semua Jenis', ...jenisOptions]"
                            :key="j"
                            @click="
                                selectedJenis = j;
                                reloadTable();
                            "
                        >
                            {{ j }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Angkatan -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ selectedAngkatan }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            v-for="a in ['Semua Angkatan', ...angkatanOptions]"
                            :key="a"
                            @click="
                                selectedAngkatan = a;
                                reloadTable();
                            "
                        >
                            {{ a }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <!-- Table -->
            <div>
                <table id="data-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Evaluasi</th>
                            <th>Jenis</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
