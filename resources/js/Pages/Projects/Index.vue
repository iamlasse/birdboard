<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Projects
        </h2>
        <jet-button
          @click.native="showCreateModal = !showCreateModal"
          class="bg-indigo-500 text-white shadow-md py-2 px-4 rounded-md"
        >Create Project</jet-button>
      </div>
    </template>
    <div class="projects-page container mx-auto p-8">
    <div class="projects-list grid grid-flow-row grid-cols-3 grid-rows-3 gap-4">
      <project-card v-for="project in projects" :key="project.id" :project="project"></project-card>
    </div>
      
    </div>




    <!-- Modal.... -->
    <jet-dialog-modal
      :show="showCreateModal"
      @close="showCreateModal = false"
    >
      <template #title>
        Create Project
      </template>

      <template #content>
        <form
          action="/projects"
          method="post"
          @submit.prevent="createProject"
        >
          <div class="p-6">
            <div class="col-span-6 sm:col-span-4">
            <jet-label for="name" value="Name" />
            <jet-input id="name" aria-placeholder="Project Title" placeholder="Project Title" type="text" class="mt-1 block w-full" v-model="projectForm.title" autocomplete="title" />
            </div>
          </div>
          <div class="p-6">
            <div class="col-span-6 sm:col-span-4">
            <jet-label for="description" value="Description" />
            <textarea
              name="description"
              id="description"
              cols="60"
              style="width: 100%"
              rows="8"
              class="form-input rounded-md shadow-sm"
              placeholder="Description goes here"
              v-model="projectForm.description"
            ></textarea>
            </div>
          </div>

        </form>
      </template>

      <template #footer>
        <jet-secondary-button @click.native="showCreateModal = false">
          Cancel
        </jet-secondary-button>
          <jet-button
            @click.native="createProject"
            class="bg-indigo-500 shadow-md p-4 text-white rounded-md"
            type="submit"
          >Create Project</jet-button>
      </template>
    </jet-dialog-modal>
  </app-layout>
</template>

<script>
import AppLayout from "./../../Layouts/AppLayout";
import DialogModal from "../../Jetstream/DialogModal";
import JetInput from '../../Jetstream/Input'
import JetLabel from '../../Jetstream/Label'
import PrimaryButton from "../../Jetstream/Button";
import SecondaryButton from "../../Jetstream/SecondaryButton";

import ProjectCard from './components/ProjectCard'
export default {
  components: {
    AppLayout,
    "jet-dialog-modal": DialogModal,
    "jet-secondary-button": SecondaryButton,
    "jet-button": PrimaryButton,
    'jet-input': JetInput,
    'jet-label': JetLabel,
    ProjectCard
  },
  props: {
    projects: Array | Object,
  },
  data() {
    return {
      showCreateModal: false,
      projectForm: this.$inertia.form(
        {
          title: null,
          description: null,
        }
      ),
    };
  },
  methods: {
    async createProject() {
      const response = await this.$inertia.post('/projects', this.projectForm, {
        preserveScroll: true
      })

      console.log(response, this.projectForm.hasErrors())
    },
  },
};
</script>

<style>
</style>