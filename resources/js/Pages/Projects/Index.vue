<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
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
      @close="showCreateModal = false; clearForm()"
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
          <div class="grid grid-cols-2">
            <div class="left border-r border-gray-100">
              <div class="p-6">
            <div class="col-span-6 sm:col-span-4">
            <jet-label for="name" value="Name" />
            <jet-input
              :class="{'border-orange-500' : error('title', 'createProject') }"
             id="name" aria-placeholder="Project Title" placeholder="Project Title" type="text" class="mt-1 block w-full" v-model="projectForm.title" autocomplete="title" />
             <jet-input-error
                :message="projectForm.error('title')"
                class="mt-2"
              />
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
              :class="{'border-orange-500' : error('description', 'createProject') }"
            ></textarea>
            <jet-input-error
                :message="projectForm.error('description')"
                class="mt-2"
              />
            </div>
          </div>
            </div>
            <div class="right tasks p-6">
              <h3 class="text-sm">Add some tasks</h3>
              <jet-input id="name" aria-placeholder="`Task ${key+1}`" :placeholder="`Task ${key+1}`" type="text" class="mt-1 block w-full" v-for="(task, key) in projectForm.tasks" :key="key" v-model="task.body" :autocomplete="`Task ${key+1}`" />
              <button class="inline-flex items-center mt-4 focus:outline-none" @click.prevent="addTask" v-if="showAddTasksButton">
                <svg class="icon icon-add-outline w-6 h-6">
                  <path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 000-16 8 8 0 000 16z"/>
                </svg>
                <span class="ml-2">Add Task</span>
              </button>
              <h4 class="text-indigo-500 text-xs font-normal dark:text-white mt-4" v-else>
                You can add more tasks on the next page after you create this project
              </h4>
            </div>
          </div>

        </form>
      </template>

      <template #footer>
        <jet-secondary-button @click.native="clearForm">
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
import JetInputError from "../../Jetstream/InputError";
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
    'jet-input-error': JetInputError,
    'jet-label': JetLabel,
    ProjectCard
  },
  props: {
    projects: Array | Object,
  },
  watch: {
    'projectForm.tasks': function(val) {
      console.log(val)
      if (val.length > 4) {
        this.showAddTasksButton = false
      } else {
        this.showAddTasksButton = true
      }
    }
  },
  data() {
    return {
      showAddTasksButton: true,
      showCreateModal: false,
      // tasks: [
      //   { body: '' }
      // ],
      projectForm: this.$inertia.form({
          title: null,
          description: null,
          tasks: [
            { body: '' }
          ]
        }, 
        {
          resetOnSuccess: true,
          preserveState: false,
          bag: 'project',
        }
      ),
    };
  },
  mounted() {
    console.log(this.$inertia)
  },
  methods: {
    addTask() {
      this.projectForm.tasks.push({ body: null })
    },
    clearForm() {
      console.info('Clear Form and Errors')
      this.showCreateModal = false
      this.$page.errors['project'] = {}
      this.$page.errorBags['project'] = {}
    },
    async createProject() {
      try {
        console.log('Create Project....', this.projectForm.hasErrors())
      await this.projectForm.post(this.$route('projects.store'), {
        preserveScroll: true
      }).then(response => {
        console.log('Posted form', response, this.projectForm.hasErrors())
        if(this.projectForm.hasErrors())
          return;
        this.projectForm.reset()
      })
      } catch(e) {
        console.error(e)
      }
    },
  },
};
</script>

<style>
</style>