<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ project.title }}
        </h2>
        <a
          href="#"
          @click.prevent="showEditModal = true"
        >
          <svg
            fill="none"
            viewBox="0 0 24 24"
            class="w-4 h-4 mr-1"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
            />
          </svg>
        </a>
      </div>
    </template>
    <div
      class="flex container mx-auto"
      style="margin-top: 2px"
    >
      <div class="project-page container mx-auto p-8 w-4/5">
        <header class="project-header flex justify-between items-center">
          <div class="left flex justify-start items-center space-x-4">
            <div class="breadcrumb ">
              <h3 class="text-gray-400">
                <inertia-link
                  class="hover:underline"
                  :href="$route('projects.index')"
                >My Projects</inertia-link> / {{ project.title }}
              </h3>
            </div>
            <!-- <jet-button
              @click.native="showTaskModal = !showTaskModal"
              class="bg-indigo-500 text-white shadow-md py-2 px-4 rounded-md"
            >Add Task</jet-button> -->
          </div>

          <div class="right flex justify-end items-center">
            <div class="avatars"></div>
            <div class="action" v-if="project.owner.id === $page.user.id">
              <jet-button @click.native="showInviteModal = true" class="bg-indigo-500 shadow-md">Invite to Project</jet-button>
            </div>
          </div>

        </header>
        <!-- Tasks -->
        <h2 class="text-gray-400 text-md font-semibold">Tasks</h2>
        <div class="task-list space-y-4">
          <div class="card opacity-50 p-0">
            <form
              action="#"
              method="post"
              @submit.prevent="addTask"
              class="w-full"
            >

              <input
                id="body"
                aria-placeholder="Project Title"
                placeholder="Add a Task here..."
                type="text"
                class="mt-1 block w-full p-2 px-4"
                v-model="taskForm.body"
                autocomplete="body"
              />

            </form>

          </div>
          <div
            class="card align-middle items-center p-4"
            v-for="task in project.tasks"
            :key="task.id"
          >
            <input
              type="text"
              v-model="task.body"
              class="w-full focus:outline-none"
              @keyup.enter="updateTask(task)"
              @update="$event.target.blur()"
              :class="{ 'text-gray-400': task.completed }"
            />
            <input
              type="checkbox"
              v-model="task.completed"
              class="flex-1"
              @input="toggleComplete(task)"
            />
          </div>

        </div>

        <!-- General Notes -->
        <div class="general-notes mt-6">
          <h2 class="text-gray-400 text-md font-semibold">General Notes</h2>
          <form
            action="#"
            @submit.prevent="updateProject"
            class="flex flex-col space-y-2"
          >
            <textarea
              name="notes"
              id="notes"
              cols="30"
              rows="10"
              class="p-6"
              v-model="updateProjectForm.notes"
            ></textarea>
            <div class="action">
              <jet-button class="bg-indigo-500 shadow-md">Save</jet-button>
            </div>
          </form>
        </div>
      </div>
      <div class="project-sidebar container mx-auto p-8 w-1/5 bg-gray-50 h-full h-screen">
        <!-- Tasks -->
        <h2 class="text-gray-400 text-md font-semibold">Latest Updates</h2>
        <ul>
          <li v-for="activity in project.activities" :key="activity.id" class="m-0 space-y-0 leading-none mb-2 order-last">
            <strong>{{ userNotation(activity) }}</strong>
            <span class="text-sm">{{ activityDecription(activity) }}</span> <small class="text-gray-500 text-xs">{{ activityTime(activity.created_at)}}</small>
          </li>
        </ul>
      </div>
    </div>
    <!-- Modal.... -->
    <jet-dialog-modal
      :show="showEditModal"
      @close="showEditModal = false"
    >
      <template #title>
        Edit: <strong>{{ project.title }}</strong>
      </template>

      <template #content>
        <jet-form-section @submitted="updateProject">

          <template #form>
            <!-- Token Name -->
            <div class="col-span-12 sm:col-span-12">
              <jet-label
                for="title"
                value="Title"
              />
              <jet-input
                id="title"
                type="text"
                class="mt-1 block w-full"
                v-model="updateProjectForm.title"
                autofocus
              />
              <jet-input-error
                :message="updateProjectForm.error('title')"
                class="mt-2"
              />
            </div>
            <!-- Token Name -->
            <div class="col-span-12 sm:col-span-12">
              <jet-label
                for="description"
                value="Description"
              />
              <textarea
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="updateProjectForm.description"
                autofocus
              ></textarea>
              <jet-input-error
                :message="updateProjectForm.error('description')"
                class="mt-2"
              />
            </div>
          </template>

          <!-- <template #actions>
                

                <jet-button :class="{ 'opacity-25': updateProjectForm.processing }" :disabled="updateProjectForm.processing">
                    Create
                </jet-button>
            </template> -->
        </jet-form-section>
      </template>

      <template #footer>
        <div class="flex items-center justify-between">
          <div class="left-buttons">
            <jet-button
              @click.native="deleteProject"
              class="bg-red-500 shadow-md p-4 text-white rounded-md"
              type="submit"
            >Delete</jet-button>
          </div>

          <div class="flex justify-end  space-x-4 right-buttons">
          <jet-action-message
            :on="updateProjectForm.recentlySuccessful"
            class="mr-3"
          >
            Updated.
          </jet-action-message>
          <jet-secondary-button @click.native="showEditModal = false">
            Cancel
          </jet-secondary-button>
          <jet-button
            @click.native="updateProject"
            class="bg-indigo-500 shadow-md p-4 text-white rounded-md"
            type="submit"
          >Update</jet-button>
        </div>
        </div>
        
      </template>
    </jet-dialog-modal>

    <jet-dialog-modal
    :show="showInviteModal"
      @close="showInviteModal = false"
    >
      <template #content>
        <jet-form-section @submitted="inviteMember">
          
          <template #form>
            <div class="col-span-12 sm:col-span-12">
              
              <jet-label
                for="email"
                value="Email"
              />
              <jet-input
                id="email"
                type="text"
                class="mt-1 block w-full"
                v-model="inviteForm.email"
                autofocus
                ref="inviteEmail"
              />
              <jet-input-error
                :message="$page.errors.email"
                class="mt-2"
              />
            </div>
            
          </template>

        </jet-form-section>
      </template>
          <template #footer>
                <jet-button @click.native="inviteMember" :class="{ 'opacity-25': inviteForm.processing }" :disabled="inviteForm.processing || !inviteForm.email">
                    Invite Member
                </jet-button>
            </template>
    </jet-dialog-modal>
  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import JetActionMessage from "../../Jetstream/ActionMessage";
import JetButton from "../../Jetstream/Button";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
import JetInput from "../../Jetstream/Input";
import JetLabel from "../../Jetstream/Label";
import JetDialogModal from "../../Jetstream/DialogModal";
import JetInputError from "../../Jetstream/InputError";
import JetFormSection from "../../Jetstream/FormSection";

import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
dayjs.extend(relativeTime)

const camelcase = text => text.replaceAll('_', ' ')

const capitalize = text => text.toLowerCase()
    .split(' ')
    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
    .join(' ');

export default {
  components: {
    AppLayout,
    "jet-action-message": JetActionMessage,
    "jet-button": JetButton,
    "jet-secondary-button": JetSecondaryButton,
    "jet-input": JetInput,
    "jet-label": JetLabel,
    "jet-dialog-modal": JetDialogModal,
    "jet-form-section": JetFormSection,
    "jet-input-error": JetInputError,
  },
  props: {
    project: Object,
  },

  data() {
    return {
      showInviteModal: false,
      showTaskModal: false,
      showEditModal: false,
      inviteForm: this.$inertia.form({
        email: null
      }, {
        bag: 'inviteMember',
        resetOnSuccess: true
      }),
      taskForm: this.$inertia.form({
        body: null,
      }),
      updateProjectForm: this.$inertia.form(
        {
          title: this.project.title,
          description: this.project.description,
          notes: this.project.notes
        },
        {
          bag: "updateProject",
          resetOnSuccess: false,
        }
      ),
    };
  },
  watch: {
    showInviteModal(modal) {
      if(!modal) {
        this.$page.errors = {}
      }
    }
  },
  methods: {
    userNotation(activity) {
      return this.$page.user.id === activity.user_id ? 'You' : activity.user.name
    },
    activityDecription(activity) {
      return `${capitalize(camelcase(activity.description))} ${capitalize(this.getSubject(activity).context)}`
    },
    getSubject(activity) {
      const { subject } = activity

      const context =  activity.changes ? Object.keys(activity.changes?.after) : ''

      return {
        context: context.length ? context.join(', ') : context,
        type: activity.subject_type
      }

    },
    activityTime(date) {
      return dayjs(date).fromNow()
    },
    locateProjectTask({ id }) {
      return this.project.tasks.find((task) => task.id === id);
    },
    async inviteMember() {
      try {
        this.inviteForm.post(this.$route('project.invite', this.project))
        .then((response) => {
          if (!Object.values(this.$page.errors).length) {
            setTimeout(() => {
              this.showInviteModal = false;
            }, 500);          
            return;
          }
          this.$refs.inviteEmail.focus()
        });
      } catch (error) {
        //
      }
    },
    async deleteProject() {
      await this.$inertia.delete(this.$route('projects.delete', this.project))
    },
    async updateTask(task) {
      try {
        await this.$inertia.patch(
          this.$route("tasks.update", {
            project: this.project,
            task,
          }),
          task
        );
      } catch (error) {
      } finally {
      }
    },
    async toggleComplete(task) {
      await this.$inertia.patch(
        this.$route("tasks.update", {
          project: this.project,
          task,
        }),
        {
          completed: !task.completed,
        }
      );
    },
    async updateProject() {
      this.updateProjectForm
        .patch(this.$route("projects.update", this.project), {
          preserveScroll: true,
          preserveState: true,
        })
        .then((response) => {
          if (!this.updateProjectForm.hasErrors()) {
            // this.displayingToken = true
            setTimeout(() => {
              this.showEditModal = false;
            }, 500);
          }
        });
    },
    async addTask() {
      const response = await this.taskForm.post(
        this.$route("project.tasks", this.project.id),
        this.taskForm,
        {
          preserveScroll: true,
        }
      );

      console.log(response, this.taskForm.hasErrors());
      this.taskForm.reset();
      this.showTaskModal = false;
    },
  },
};
</script>

<style>
</style>