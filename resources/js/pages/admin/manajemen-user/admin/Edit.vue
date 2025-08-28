<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    role: String,
});

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    password: '',
    password_confirmation: '',
    role: 'admin',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Admin', href: route('admin.users.index', 'admin') },
    { title: 'Edit Data Admin', href: route('admin.users.edit', { role: 'admin', id: props.user.id }) },
];

const submit = () => {
    form.put(route('admin.users.update', { role: 'admin', id: props.user.id }));
};
</script>

<template>
    <Head title="Edit Data Admin" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Data Admin</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama -->
                <div class="flex flex-col gap-3">
                    <Label for="name">Nama</Label>
                    <Input id="name" v-model="form.name" placeholder="Masukkan nama lengkap" />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-3">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" v-model="form.email" placeholder="Masukkan email" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" v-model="form.password" placeholder="Minimal 8 karakter" />
                    <InputError :message="form.errors.password" />
                    <span class="text-xs text-yellow-500 italic">Note: Kosongkan jika tidak ingin diubah</span>
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Ulangi password" />
                    <InputError :message="form.errors.password_confirmation" />
                    <span class="text-xs text-yellow-500 italic">Note: Kosongkan jika tidak ingin diubah</span>
                </div>

                <!-- Submit -->
                <div class="mt-4 flex gap-2 md:col-span-2">
                    <Button variant="outline" type="button" @click="router.visit(route('admin.users.index', 'admin'))">Batal</Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Update
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
