<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, CalendarDays, LayoutGrid, MessageCircle, TrendingUp } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        isActive: true,
    },
    {
        title: 'Akademik',
        href: '/akademik',
        icon: CalendarDays,
        isActive: false,
        children: [
            { title: 'Absensi', href: '/akademik/absensi' },
            { title: 'Kelas', href: '/akademik/kelas' },
            { title: 'Jadwal Pelajaran', href: '/akademik/jadwal-pelajaran' },
            { title: 'Mata Pelajaran', href: '/akademik/matpel' },
            { title: 'Siswa', href: '/akademik/siswa' },
            { title: 'Guru', href: '/akademik/guru' },
            { title: 'Kurikulum', href: '/akademik/kurikulum' },
            { title: 'Input Nilai', href: '/akademik/input-nilai' },
        ],
    },
    {
        title: 'Materi',
        href: '/materi',
        icon: BookOpen,
        isActive: false,
    },
    {
        title: 'Adaptive Learning',
        href: '/adaptive-learning',
        icon: TrendingUp,
        isActive: false,
    },
    {
        title: 'Forum Diskusi',
        href: '/forum-diskusi',
        icon: MessageCircle,
        isActive: false,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
