<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const form = useForm({
    kode_mapel: '',
    nama_mapel: '',
    deskripsi: '',
    guru_id: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Mata Pelajaran', href: route('admin.mata-pelajaran.index') },
    { title: 'Tambah Mata Pelajaran', href: route('admin.mata-pelajaran.create') },
];

const props = defineProps<{
    guruOptions: { id: string; name: string }[];
}>();

const selectedGuruLabel = ref('Pilih Guru');

// Sinkronisasi label saat guru_id berubah
watch(
    () => form.guru_id,
    (val) => {
        const selected = props.guruOptions.find((g) => g.id === val);
        selectedGuruLabel.value = selected ? selected.name : 'Pilih Guru';
    },
);

const submit = () => {};
</script>

<template>
    <Head title="Tambah Mata Pelajaran" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Mata Pelajaran</h1>

            <form class="grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <!-- Kode Mapel -->
                <div class="flex flex-col gap-3">
                    <Label for="kode_mapel">Kode Mapel</Label>
                    <Input id="kode_mapel" v-model="form.kode_mapel" placeholder="Contoh: MTK01" />
                    <InputError :message="form.errors.kode_mapel" />
                </div>

                <!-- Nama Mapel -->
                <div class="flex flex-col gap-3">
                    <Label for="nama_mapel">Nama Mata Pelajaran</Label>
                    <Input id="nama_mapel" v-model="form.nama_mapel" placeholder="Contoh: Matematika" />
                    <InputError :message="form.errors.nama_mapel" />
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="deskripsi">Deskripsi (Opsional)</Label>
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        rows="4"
                        class="rounded border p-2 text-sm"
                        placeholder="Tambahkan deskripsi singkat..."
                    />
                    <InputError :message="form.errors.deskripsi" />
                </div>

                <!-- Guru Pengampu -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="guru_id">Guru Pengampu</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full items-center justify-between rounded border px-4 py-2 text-left">
                                <span class="truncate text-sm">{{ selectedGuruLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-[200px] min-w-[200px] overflow-y-auto">
                            <DropdownMenuItem
                                v-for="guru in props.guruOptions"
                                :key="guru.id"
                                @click="
                                    form.guru_id = guru.id;
                                    selectedGuruLabel = guru.name;
                                "
                            >
                                {{ guru.name }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.guru_id" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
