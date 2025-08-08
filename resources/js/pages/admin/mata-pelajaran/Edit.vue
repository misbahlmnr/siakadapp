<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

type Form = {
    kode_mapel: string;
    nama_mapel: string;
    deskripsi: string;
};

const props = defineProps<{
    mataPelajaran: {
        id: number;
        kode_mapel: string;
        nama_mapel: string;
        deskripsi: string | null;
        created_at: string;
        updated_at: string;
    };
}>();

const form = useForm<Form>({
    kode_mapel: props.mataPelajaran.kode_mapel,
    nama_mapel: props.mataPelajaran.nama_mapel,
    deskripsi: props.mataPelajaran.deskripsi ?? '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Mata Pelajaran', href: route('admin.mata-pelajaran.index') },
    { title: 'Edit Mata Pelajaran', href: route('admin.mata-pelajaran.edit', props.mataPelajaran.id) },
];

const submit = () => {
    form.put(route('admin.mata-pelajaran.update', props.mataPelajaran.id));
};
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
