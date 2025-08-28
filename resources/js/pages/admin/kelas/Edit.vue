<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    kelas: {
        id: number;
        nama_kelas: string;
        tingkat: string;
        tahun_ajaran: string;
    };
}>();

type Form = {
    nama_kelas: string;
    tingkat: string;
    tahun_ajaran: string;
};

const form = useForm<Form>({
    nama_kelas: props.kelas.nama_kelas,
    tingkat: props.kelas.tingkat,
    tahun_ajaran: props.kelas.tahun_ajaran,
});

const tingkatList = ['VII', 'VIII', 'IX'];
const selectedTingkatLabel = ref(props.kelas.tingkat || 'Pilih Tingkat');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Kelas', href: route('admin.kelas.index') },
    { title: `Edit Kelas - ${props.kelas.nama_kelas}`, href: route('admin.kelas.edit', props.kelas.id) },
];

const submit = () => {
    form.put(route('admin.kelas.update', props.kelas.id));
};
</script>

<template>
    <Head :title="`Edit Kelas - ${props.kelas.nama_kelas}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Kelas</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="nama_kelas">Nama Kelas</Label>
                    <Input id="nama_kelas" v-model="form.nama_kelas" placeholder="Contoh: VII A" />
                    <InputError :message="form.errors.nama_kelas" />
                </div>

                <!-- Tingkat -->
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <Label>Tingkat</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                                    <span class="text-sm">{{ selectedTingkatLabel }}</span>
                                    <ChevronDown class="h-4 w-4 text-gray-500" />
                                </button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                                <DropdownMenuItem
                                    v-for="tingkat in tingkatList"
                                    :key="tingkat"
                                    @click="
                                        form.tingkat = tingkat;
                                        selectedTingkatLabel = tingkat;
                                    "
                                >
                                    {{ tingkat }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <InputError :message="form.errors.tingkat" />
                    </div>
                </div>

                <!-- Tahun Ajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="tahun_ajaran">Tahun Ajaran</Label>
                    <Input id="tahun_ajaran" v-model="form.tahun_ajaran" placeholder="Contoh: 2025/2026" />
                    <InputError :message="form.errors.tahun_ajaran" />
                </div>

                <!-- Submit -->
                <div class="mt-4 flex gap-2 md:col-span-2">
                    <Button variant="outline" type="button" @click="router.visit(route('admin.kelas.index'))">Batal</Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Update
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
