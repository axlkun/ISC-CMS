<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, defineProps, onMounted  } from 'vue';
import { strSlug } from "@/helpers.js";

import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Container from '@/OwnComponents/Container.vue';
import Card from '@/OwnComponents/Card.vue';
import BreadCroumbs from '@/OwnComponents/BreadCroumbs.vue';

const props = defineProps({
    edit: Boolean,
    service: Object
});

const form = useForm({
    name: "",
    slug: ""
});

const breadcrumbs = [
    {
        label: "Servicios",
        url: route('services.index')
    },
    {
        label: `${props.edit ? 'Edit' : 'Add'} Service`
    }
];

watch(
    () => form.name, // use a getter like this
    (name) => {
        form.slug = strSlug(name);
    }
)

onMounted(() => {
    if (props.edit) {
        form.name = props.service.data.name
        form.slug = props.service.data.slug
    }
});

const saveService = () => {

    props.edit 
    ? form.put(route('services.update', {id: props.service.data.id}))
    : form.post(route('services.store'));
};
</script>

<template>
    <AppLayout title="Servicio">
        <template #header>
            <BreadCroumbs :items="breadcrumbs"></BreadCroumbs>
        </template>

        <Container>
            <Card>
                <form @submit.prevent="saveService">
                    <div>
                        <InputLabel for="name" value="Nombre" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                            autocomplete="name" />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="slug" value="Slug" />
                        <TextInput disabled="disabled" id="slug" v-model="form.slug" type="text" class="mt-1 block w-full" required
                            autocomplete="slug" />
                        <InputError :message="form.errors.slug" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                            Saved.
                        </ActionMessage>

                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <span v-if="edit">Update</span>
                            <span v-else>Save</span>
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </Container>

    </AppLayout>
</template>
