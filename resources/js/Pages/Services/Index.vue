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
    services: Object,
});

const headers = [
    { name: "Nombre" },
    { name: "Slug" },
    { name: "Fecha de creacion" },
    {
        name: "Acciones",
        class: "text-right"
    }
];

const breadcrumbs = [
    {
        label: "Servicios"
    }
];

</script>

<template>
    <AppLayout title="Servicios">
        <template #header>
            <BreadCroumbs :items="breadcrumbs"></BreadCroumbs>
        </template>

        <Container>
            <PrimaryButton :href="route('services.create')">Nuevo Servicio</PrimaryButton>

            <Card class="mt-4">
                <AppTable :headers="headers" :items="services">
                    <tr v-for="service in services.data" :key="service.id">
                        <td>{{ service.name }}</td>
                        <td>{{ service.slug }}</td>
                        <td>{{ service.created_at_formated }}</td>
                        <td>
                            <div class="flex items-center justify-end space-x-2">
                                <EditBtn :url="route('services.edit', { service: service.id })"></EditBtn>

                                <DeleteBtn :url="route('services.destroy', { service: service.id })"
                                    module-name="servicio"></DeleteBtn>
                            </div>
                        </td>
                    </tr>
                </AppTable>
            </Card>
        </Container>

    </AppLayout>
</template>
