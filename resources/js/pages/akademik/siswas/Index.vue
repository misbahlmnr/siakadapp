<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import $ from 'jquery';
import { FileUp, Plus, SquarePen, Trash2 } from 'lucide-vue-next';
import { nextTick, onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Akademik',
        href: '/akademik',
    },
    {
        title: 'Siswa',
        href: '/akademik/siswa',
    },
];

const page = usePage();
const toast = useToast();

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) toast.success(flash.success);
        if (flash?.error) toast.error(flash.error);
    },
    { immediate: true },
);

const siswaList = ref<any[]>([]);

const edit = (id: number) => {
    console.log('Edit siswa id:', id);
};

const hapus = (id: number) => {
    console.log('Hapus siswa id:', id);
};

onMounted(async () => {
    const res = await axios.get(route('akademik.siswa.get'));
    siswaList.value = res.data.data;

    // Tunggu DOM render
    await nextTick();

    // Init DataTables
    $('#siswaTable').DataTable({
        pagingType: 'simple_numbers',
        language: {
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            zeroRecords: 'Tidak ada data ditemukan',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
            infoFiltered: '(disaring dari _MAX_ total data)',
            search: 'Cari:',
            paginate: {
                previous: '‹',
                next: '›',
            },
        },
        columnDefs: [
            {
                targets: 0,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
        ],
    });
});
</script>

<template>
    <Head title="Data Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Data Siswa</h1>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        class="flex cursor-pointer items-center justify-center gap-2 rounded-md bg-cyan-600 px-4 py-2.5 text-sm text-white hover:bg-cyan-500"
                    >
                        <FileUp :size="18" />
                        Upload
                    </button>
                    <Link :href="route('akademik.siswa.create')">
                        <button
                            class="flex items-center justify-center gap-2 rounded-md bg-purple-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-purple-500"
                        >
                            <Plus :size="18" />
                            Tambah Data
                        </button>
                    </Link>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table id="siswaTable" class="table-bordered table-striped table w-full">
                    <thead class="table-head bg-purple-500">
                        <tr>
                            <th>NO</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelas</th>
                            <th>Tahun Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <tr v-for="siswa in siswaList" :key="siswa.id">
                            <td></td>
                            <td>{{ siswa.nis }}</td>
                            <td>{{ siswa.nama }}</td>
                            <td>{{ siswa.jenis_kelamin }}</td>
                            <td>{{ siswa.kelas }}</td>
                            <td>{{ siswa.tahun_masuk }}</td>
                            <td>
                                <button class="text-blue-600 hover:cursor-pointer hover:underline" @click="edit(siswa.id)">
                                    <SquarePen :size="16" />
                                </button>
                                |
                                <button class="text-red-600 hover:cursor-pointer hover:underline" @click="hapus(siswa.id)">
                                    <Trash2 :size="16" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
