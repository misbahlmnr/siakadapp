<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

type Props = {
    kelas: {
        id: number;
        nama_kelas: string;
        tingkat_kelas: string;
    };
};

const props = defineProps<Props>();

const form = useForm({
    nama_kelas: props.kelas.nama_kelas,
    tingkat_kelas: props.kelas.tingkat_kelas,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Kelas', href: route('admin.kelas.index') },
    { title: 'Edit Kelas', href: route('admin.kelas.edit', props.kelas.id) },
];

const submit = () => {
    form.put(route('admin.kelas.update', props.kelas.id));
};
</script>

<template>
    <Head title="Edit Kelas" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Kelas</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="nama_kelas">Nama Kelas</Label>
                    <Input id="nama_kelas" v-model="form.nama_kelas" placeholder="Contoh: X IPA 1" />
                    <InputError :message="form.errors.nama_kelas" />
                </div>

                <!-- Tingkat -->
                <div class="flex flex-col gap-3">
                    <Label for="tingkat">Tingkat</Label>
                    <Input id="tingkat" v-model="form.tingkat_kelas" placeholder="Contoh: X / XI / XII" />
                    <InputError :message="form.errors.tingkat_kelas" />
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
