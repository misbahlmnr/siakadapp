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

type Form = {
    kode_mapel: string;
    nama_mapel: string;
    deskripsi: string;
    guru_id: number | null;
};

const props = defineProps<{
    mataPelajaran: {
        id: number;
        kode_mapel: string;
        nama_mapel: string;
        deskripsi: string | null;
        guru_id: number | null;
    };
    guruOptions: { id: number; name: string }[];
}>();

const form = useForm<Form>({
    kode_mapel: props.mataPelajaran.kode_mapel,
    nama_mapel: props.mataPelajaran.nama_mapel,
    deskripsi: props.mataPelajaran.deskripsi ?? '',
    guru_id: props.mataPelajaran.guru_id,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Mata Pelajaran', href: route('admin.mata-pelajaran.index') },
    { title: 'Edit Mata Pelajaran', href: route('admin.mata-pelajaran.edit', props.mataPelajaran.id) },
];

const selectedGuruLabel = ref('Pilih Guru');

// Sinkronisasi label saat guru_id berubah
watch(
    () => form.guru_id,
    (val) => {
        const selected = props.guruOptions.find((g) => g.id === val);
        console.log('guru options', props.guruOptions);
        selectedGuruLabel.value = selected ? selected.name : 'Pilih Guru';
    },
    { immediate: true },
);

const submit = () => {
    form.put(route('admin.mata-pelajaran.update', props.mataPelajaran.id));
};
console.log(props.guruOptions);
console.log(props.mataPelajaran);
</script>

<template>
    <Head title="Edit Mata Pelajaran" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Mata Pelajaran</h1>

            <form class="grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <!-- Kode Mapel -->
                <div class="flex flex-col gap-3">
                    <Label for="kode_mapel">Kode Mapel</Label>
                    <Input id="kode_mapel" v-model="form.kode_mapel" />
                    <InputError :message="form.errors.kode_mapel" />
                </div>

                <!-- Nama Mapel -->
                <div class="flex flex-col gap-3">
                    <Label for="nama_mapel">Nama Mata Pelajaran</Label>
                    <Input id="nama_mapel" v-model="form.nama_mapel" />
                    <InputError :message="form.errors.nama_mapel" />
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="deskripsi">Deskripsi</Label>
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
                        Simpan Perubahan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
