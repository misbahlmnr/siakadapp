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
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Absensi Siswa', href: route('guru.absensi.index') },
];

// State filter
const selectedKelas = ref('Semua Kelas');
const selectedMapel = ref('Semua Mapel');
const selectedStatus = ref('Semua Status');
const selectedPertemuan = ref('Semua Pertemuan');

const statusOptions = ['hadir', 'sakit', 'ijin', 'alfa'];
const pertemuanOptions = Array.from({ length: 20 }, (_, i) => `Pertemuan ${i + 1}`);

let table: any;

function reloadTable() {
    if (table) table.ajax.reload();
}

onMounted(() => {
    table = useDataTable('#data-table', {
        ajax: {
            url: route('guru.absensi.data'),
            data: function (d) {
                d.kelas = selectedKelas.value === 'Semua Kelas' ? '' : selectedKelas.value;
                d.mapel = selectedMapel.value === 'Semua Mapel' ? '' : selectedMapel.value;
                d.status = selectedStatus.value === 'Semua Status' ? '' : selectedStatus.value;
                d.pertemuan = selectedPertemuan.value === 'Semua Pertemuan' ? '' : selectedPertemuan.value.replace('Pertemuan ', '');
            },
        },
        detailRoute: 'guru.absensi.show',
        editRoute: 'guru.absensi.edit',
        deleteRoute: 'guru.absensi.destroy',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'siswa', name: 'siswa' },
            { data: 'kelas', name: 'kelas' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'pertemuan_ke', name: 'pertemuan_ke' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'status', name: 'status' },
        ],
    });
});
</script>

<template>
    <Head title="Absensi Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Absensi Siswa</h1>
                <Button class="bg-blue-600 text-white hover:bg-blue-600/90" @click="router.visit(route('guru.absensi.create'))">
                    Tambah Absensi
                </Button>
            </div>

            <!-- Filters -->
            <div class="mb-4 flex flex-wrap gap-4">
                <!-- Kelas -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex items-center justify-between rounded border px-4 py-2" type="button">
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
                        <button class="flex items-center justify-between rounded border px-4 py-2" type="button">
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

                <!-- Status Kehadiran -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ selectedStatus }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <DropdownMenuItem
                            v-for="s in ['Semua Status', ...statusOptions]"
                            :key="s"
                            @click="
                                selectedStatus = s;
                                reloadTable();
                            "
                        >
                            {{ s }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Pertemuan -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button class="flex items-center justify-between rounded border px-4 py-2" type="button">
                            <span class="text-sm">{{ selectedPertemuan }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            v-for="p in ['Semua Pertemuan', ...pertemuanOptions]"
                            :key="p"
                            @click="
                                selectedPertemuan = p;
                                reloadTable();
                            "
                        >
                            {{ p }}
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
