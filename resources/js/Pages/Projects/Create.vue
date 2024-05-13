<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, defineProps, onMounted, ref } from 'vue';
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
import AppImage from '@/OwnComponents/Image.vue';
import AppCkeditor from '@/OwnComponents/Ckeditor.vue';
import AppTexArea from '@/OwnComponents/TextArea.vue';

const props = defineProps({
    edit: Boolean,
    project: Object,
    services: {
        type: Object,
        default: function () {
            return {
                data: [],
            };
        },
    },
});

const form = useForm({
    "_method": props.edit ? 'PUT' : "",
    services: [],
    title: "",
    slug: "",
    summary: "",
    location: "",
    date: "",
    description: "",
    image: null
});

let imageUrl = ref("");

const breadcrumbs = [
    {
        label: "Projects",
        url: route('projects.index')
    },
    {
        label: `${props.edit ? 'Edit' : 'Add'} Project`
    }
];

watch(
    () => form.title,
    (title) => {
        form.slug = strSlug(title);
    }
)

onMounted(() => {
    if (props.edit) {
        form.services = props.project.data.services.map(service => service.id);
        form.title = props.project.data.title;
        form.slug = props.project.data.slug;
        form.summary = props.project.data.summary;
        form.description = props.project.data.description;
        form.date = props.project.data.date;
        form.location = props.project.data.location;
    }

    imageUrl.value = props.project.data.imageUrl;

});

const saveProject = () => {

    props.edit
        ? form.post(route('projects.update', { id: props.project.data.id }))
        : form.post(route('projects.store'));
};

</script>

<template>
    <AppLayout title="Project">
        <template #header>
            <BreadCroumbs :items="breadcrumbs"></BreadCroumbs>
        </template>

        <Container>
            <Card>
                <form @submit.prevent="saveProject" enctype="multipart/form-data">
                    <div class="col-span-6 sm:col-span-4">

                        <AppImage :imageUrl="imageUrl" label="Image" v-model="form.image" :errorMessage="form.errors.image">
                        </AppImage>

                    </div>
                    
                    <div class="mt-4" style="border: 1px solid grey;">
                        <InputLabel for="service" value="Service" />
                        <MultiSelect v-model="form.services" display="chip" :options="services.data" optionLabel="name"
                            optionValue="id" placeholder="Select service" :maxSelectedLabels="5"
                            class="w-full md:w-20rem" />
                        <InputError :message="form.errors.service" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="title" value="Title" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required
                            autocomplete="title" />
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="slug" value="Slug" />
                        <TextInput disabled="disabled" id="slug" v-model="form.slug" type="text" class="mt-1 block w-full"
                            required autocomplete="slug" />
                        <InputError :message="form.errors.slug" class="mt-2" />
                    </div>


                    <div class="mt-4">
                        <InputLabel for="summary" value="Summary" />

                        <AppTexArea id="summary" v-model="form.summary" type="text" class="mt-1 block w-full" required
                            autocomplete="summary"></AppTexArea>

                        <InputError :message="form.errors.summary" class="mt-2" />

                    </div>

                    <div class="mt-4">
                        <InputLabel for="location" value="Location" />
                        <TextInput id="location" v-model="form.location" type="text" class="mt-1 block w-full" required
                            autocomplete="location" />
                        <InputError :message="form.errors.location" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="date" value="Date" />
                        <TextInput id="date" v-model="form.date" type="date" class="mt-1 block w-full" required
                            autocomplete="date" />
                        <InputError :message="form.errors.date" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="description" value="Description" />

                        <AppCkeditor v-model="form.description"></AppCkeditor>

                        <InputError :message="form.errors.description" class="mt-2" />
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
