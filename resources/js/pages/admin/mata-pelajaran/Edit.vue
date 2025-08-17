<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, MatPel } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    guruList: { id: number; name: string }[];
    mataPelajaran: MatPel;
}>();

type Form = {
    kode_mapel: string;
    nama_mapel: string;
    guru_id: number | string | null;
};

// inisialisasi form dengan data existing
const form = useForm<Form>({
    kode_mapel: props.mataPelajaran.kode_mapel,
    nama_mapel: props.mataPelajaran.nama_mapel,
    guru_id: props.mataPelajaran.guru_id,
});

// set label dropdown sesuai data guru_id
const selectedGuruLabel = ref(
    props.mataPelajaran.guru_id ? (props.guruList.find((g) => g.id === props.mataPelajaran.guru_id)?.name ?? 'Pilih Guru') : 'Pilih Guru',
);

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
    <Head :title="`Edit Mata Pelajaran - ${form.nama_mapel}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Mata Pelajaran</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
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

                <!-- Guru -->
                <div class="flex flex-col gap-2">
                    <Label>Guru Pengampu</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                                <span class="text-sm">{{ selectedGuruLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="guru in guruList"
                                :key="guru.id"
                                @click="
                                    () => {
                                        form.guru_id = guru.id;
                                        selectedGuruLabel = guru.name;
                                    }
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
                    <Button variant="outline" type="button" @click="router.visit(route('admin.mata-pelajaran.index'))">Batal</Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
