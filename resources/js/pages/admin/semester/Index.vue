<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ChevronDown, Plus } from 'lucide-vue-next';
import 'sweetalert2/dist/sweetalert2.min.css';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Semester', href: route('admin.semester.index') },
];

// options untuk filter
const statusOptions = ['Semua', 'Aktif', 'Nonaktif'];
const selectedStatus = ref('Semua');

let dataTable: any = null;

onMounted(() => {
    dataTable = useDataTable('#data-table', {
        ajax: {
            url: route('admin.semester.data'),
            data: function (d: any) {
                if (selectedStatus.value === 'Aktif') {
                    d.status_aktif = 1;
                } else if (selectedStatus.value === 'Nonaktif') {
                    d.status_aktif = 0;
                } else {
                    delete d.status_aktif;
                }
            },
        },
        editRoute: 'admin.semester.edit',
        deleteRoute: 'admin.semester.destroy',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'semester', name: 'semester' },
            { data: 'tahun_ajaran', name: 'tahun_ajaran' },
            {
                data: 'status_aktif',
                name: 'status_aktif',
                render: (data: any) => (data ? 'Aktif' : 'Nonaktif'),
            },
        ],
    });
});

function applyFilter(option: string) {
    selectedStatus.value = option;
    dataTable.ajax.reload();
}
</script>

<template>
    <Head title="Daftar Semester" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Daftar Semester</h1>
                <Button
                    class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
                    @click="router.visit(route('admin.semester.create'))"
                >
                    <Plus :size="18" />
                    Tambah Data
                </Button>
            </div>

            <!-- Filter dropdown -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium">Filter Status:</label>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button type="button" class="flex w-[200px] items-center justify-between rounded border px-4 py-2 text-sm">
                            <span>{{ selectedStatus }}</span>
                            <ChevronDown class="h-4 w-4 text-gray-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                        <DropdownMenuItem v-for="opt in statusOptions" :key="opt" @click="applyFilter(opt)">
                            {{ opt }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <div>
                <table id="data-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Semester</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
