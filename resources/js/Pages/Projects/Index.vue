<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import EditBtn from '@/OwnComponents/EditBtn.vue';
import DeleteBtn from '@/OwnComponents/DeleteBtn.vue';
import AppTable from '@/OwnComponents/Table.vue';
import Container from '@/OwnComponents/Container.vue';
import Card from '@/OwnComponents/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import BreadCroumbs from '@/OwnComponents/BreadCroumbs.vue';

const props = defineProps({
    projects: Object,
});

const headers = [
    { name: "Titulo" },
    { name: "Servicio" },
    { name: "Fecha de creacion" },
    {
        name: "Acciones",
        class: "text-right"
    }
];

const breadcrumbs = [
    {
        label: "Proyectos"
    }
];

</script>

<template>
    <AppLayout title="Proyectos">
        <template #header>
            <BreadCroumbs :items="breadcrumbs"></BreadCroumbs>
        </template>

        <Container>
            <PrimaryButton :href="route('projects.create')">Nuevo proyecto</PrimaryButton>

            <Card class="mt-4">
                <AppTable :headers="headers" :items="projects">
                    <tr v-for="project in projects.data" :key="project.id">
                        <td>{{ project.title }}</td>
                        <td>
                            <!-- Iterar sobre los servicios del proyecto -->
                            <span v-for="(service, index) in project.services" :key="index">
                                {{ service.name }}
                                <!-- Agregar coma y espacio después de cada servicio, excepto el último -->
                                <span v-if="index !== project.services.length - 1">, </span>
                            </span>
                        </td>
                        <td>{{ project.date }}</td>
                        <td>
                            <div class="flex items-center justify-end space-x-2">
                                <EditBtn :url="route('projects.edit', { project: project.id })"></EditBtn>

                                <DeleteBtn :url="route('projects.destroy', { project: project.id })"
                                    module-name="proyecto"></DeleteBtn>
                            </div>
                        </td>
                    </tr>
                </AppTable>
            </Card>
        </Container>

    </AppLayout>
</template>
