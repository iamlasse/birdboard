<template>
  <app-layout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
          {{ project.title }}
        </h2>
        <div class="flex items-center justify-end actions">
          <div class="mr-10 refresh" v-if="refreshProject">
            <button @click="handleRefresh" class="px-4 py-1 text-xs text-white bg-indigo-500 rounded-full focus:outline-none">Refresh for updates</button>
          </div>
          <div class="flex present-members" v-if="presentMembers.length">
            <div class="flex mr-4 avatars ">
              <div
                class="-ml-6 member"
                v-for="(member) in presentMembers"
                :key="member.id"
              >
                <img
                  :src="member.avatar"
                  alt="User Avatar"
                  class="w-10 h-10 border border-gray-200 rounded-full"
                >
              </div>
            </div>
          </div>
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
      </div>
    </template>
    <div
      class="container flex mx-auto"
      style="margin-top: 2px"
    >
      <div class="container w-4/5 p-8 mx-auto project-page">
        <header class="flex items-center justify-between project-header">
          <div class="flex items-center justify-start space-x-4 left">
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
              class="px-4 py-2 text-white bg-indigo-500 rounded-md shadow-md"
            >Add Task</jet-button> -->
          </div>

          <div class="flex items-center justify-end right">
            <div class="flex mr-4 avatars">
              <div
                class="-ml-6 member"
                v-for="(member) in members"
                :key="member.id"
              >
                <img
                  :src="member.avatar"
                  alt="User Avatar"
                  class="w-10 h-10 border border-gray-200 rounded-full"
                >
              </div>
            </div>
            <div
              class="action"
              v-if="project.owner.id === $page.user.id"
            >
              <jet-button
                @click.native="showInviteModal = true"
                class="bg-indigo-500 shadow-md"
              >Invite to Project</jet-button>
            </div>
          </div>

        </header>
        <!-- Tasks -->
        <h2 class="font-semibold text-gray-400 text-md">Tasks</h2>
        <div class="space-y-4 task-list">
          <div class="p-0 opacity-50 card bg-gray-50 dark:bg-gray-800">
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
                class="block w-full p-2 px-4 mt-1"
                v-model.lazy="taskForm.body"
                autocomplete="body"
              />

            </form>
          </div>
          <div
            class=""
            v-for="task in project.tasks"
            :key="task.id"
          >
            <div class="flex items-center justify-between p-4 align-middle card bg-gray-50 dark:bg-gray-800">
              <input
              type="text"
              v-model="task.body"
              class="w-full text-gray-700 focus:outline-none bg-gray-50 dark:bg-gray-800 dark:text-white"
              @keyup.enter="updateTask(task)"
              @update="$event.target.blur()"
              :class="{ 'text-gray-400': task.completed }"
            />
            <input
              type="checkbox"
              v-model="task.completed"
              class="flex-1 bg-gray-50 dark:bg-gray-800"
              @input="toggleComplete(task)"
            />
            </div>
          <div class="flex justify-between mt-1">
            <span class="text-xs text-gray-500">{{ userNotation(task) }}</span>
            <small class="text-gray-500 teext-xs">{{ activityTime(task.created_at) }}</small>
          </div>
          </div>
        </div>

        <!-- General Notes -->
        <div class="mt-6 general-notes">
          <h2 class="font-semibold text-gray-400 text-md">General Notes</h2>
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
              class="p-6 text-gray-800 outline-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:outline-none"
              v-model="updateProjectForm.notes"
            ></textarea>
            <div class="action">
              <jet-button class="bg-indigo-500 shadow-md">Save</jet-button>
            </div>
          </form>
        </div>
      </div>
      <div class="container w-1/5 p-8 mx-auto project-sidebar bg-gray-50 dark:bg-gray-800">
        <!-- Tasks -->
        <h2 class="font-semibold text-gray-400 text-md">Latest Updates</h2>
        <ul>
          <li
            v-for="activity in activities.slice(0, 10)"
            :key="activity.id"
            class="order-last m-0 mb-2 space-y-0 leading-none text-gray-800 dark:text-gray-50"
          >
            <strong class="text-gray-800 dark:text-gray-50">{{ userNotation(activity) }}</strong><br/>
            <span class="text-sm text-gray-800 dark:text-gray-50">{{ activityDecription(activity) }}</span> <br/>
            <small class="text-xs text-gray-500">{{ activityTime(activity.created_at)}}</small>
          </li>
        </ul>

        <inertia-link
          class="mt-4 text-teal-500"
          v-if="project.activities.length > 10"
          href="1"
        >See all activity ({{activities.length}}) </inertia-link>
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
                class="block w-full mt-1"
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
                class="block w-full mt-1"
                v-model="updateProjectForm.description"
                autofocus
              ></textarea>
              <jet-input-error
                :message="updateProjectForm.error('description')"
                class="mt-2"
              />
            </div>
          </template>
        </jet-form-section>
      </template>

      <template #footer>
        <div class="flex items-center justify-between">
          <div class="left-buttons">
            <jet-button
              v-if="isOwner"
              @click.native="deleteProject"
              class="p-4 text-white bg-red-500 rounded-md shadow-md"
              type="submit"
            >Delete</jet-button>
          </div>

          <div class="flex items-center justify-end space-x-4 right-buttons">
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
              class="p-4 text-white bg-indigo-500 rounded-md shadow-md"
              type="submit"
            >Update</jet-button>
          </div>
        </div>

      </template>
    </jet-dialog-modal>

    <jet-dialog-modal
      :show="showInviteModal"
      @close="showInviteModal = false; resetInviteForm()"
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
                class="block w-full mt-1"
                v-model="inviteForm.email"
                autofocus
                ref="inviteEmail"
              />
              <jet-input-error
                :message="inviteForm.error('email')"
                class="mt-2"
              />
            </div>

          </template>

        </jet-form-section>
      </template>
      <template #footer>
        <jet-button
          @click.native="inviteMember"
          :class="{ 'opacity-25': inviteForm.processing }"
          :disabled="inviteForm.processing || !inviteForm.email"
        >
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

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

const camelcase = (text) => text.replaceAll("_", " ");

const capitalize = (text) =>
  text
    .toLowerCase()
    .split(" ")
    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
    .join(" ");

export default {
  inject: ["echo"],
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
      refreshProject: false,
      showInviteModal: false,
      showTaskModal: false,
      showEditModal: false,
      participants: [],
      inviteForm: this.$inertia.form(
        {
          email: null,
        },
        {
          bag: "project",
          resetOnSuccess: true,
        }
      ),
      taskForm: this.$inertia.form({
        user_id: this.$page.user.id,
        body: null,
      }),
      updateProjectForm: this.$inertia.form(
        {
          title: this.project.title,
          description: this.project.description,
          notes: this.project.notes,
        },
        {
          bag: "project",
          resetOnSuccess: false,
          preserveState: true,
          preserveScroll: true
        }
      ),
    };
  },
  watch: {
    showInviteModal(modal) {
      if (!modal) {
        this.$page.errors = {};
      }
    },
  },
  computed: {
    activities() {
      return this.project.activities;
    },
    tasks() {
      return this.project.tasks;
    },
    owner() {
      return this.project.owner;
    },
    members() {
      return this.isOwner
        ? this.project.members
        : [...this.project.members, this.owner];
    },
    isOwner() {
      return this.project.owner.id === this.$page.user.id;
    },
    chat() {
      return this.echo.join("projects-chat." + this.project?.id)
    },
    presentMembers(){
      return this.participants.filter(u => u.id !== this.$page.user.id)
    }
  },
  mounted() {
    this.bindChannels();
  },
  methods: {
    bindChannels() {
      console.info("Bind all channels: ", this.echo.connector.channels, window.Echo);
      
      // Chat Events...
      this.chat
        .here(users => {
          console.info('Present Users: ', users)
          this.participants = users
        })
        .joining(user => {
          console.info('Joining User: ', user)
          this.participants = this.participants.find(u => u.id === user.id) ? this.participants : this.participants.concat(user) 
        })
        .leaving(user => {
          console.info('User Leaving: ', user, this.participants)
          this.participants.splice(this.participants.indexOf(user), 1)
          console.log('Participants: ', this.participants)
        })
      
      // Project Events
      this.echo
        .private(`projects.${this.project?.id}`)
        .listen("ProjectUpdated", (e) => {
          console.log("Project Updated: ", e.project);
          this.refreshProject = true
        });
    },
    resetInviteForm() {
      this.inviteForm.reset();
      this.$page.errorBags["project"] = {};
    },
    userNotation(activity) {
      if(this.$page.user.id === activity.user_id) {
        return 'You'
      }
      if(this.members.length > 0) {
        const user = this.members.find(m => m.id === activity.user_id)
        return user ? user.name : 'Unknown'
      }
    },
    activityDecription(activity) {
      const description = {
        'created_project': "Created the project",
        'updated_project': "Updated the project",
        'created_task': 'Created a task',
        'completed_task': 'Completed a task',
        'marked_task_incomplete': 'Marked a task incomplete'
      }[activity.description] || ''

      return `${description} ${capitalize(
        this.getSubject(activity).context
      )}`
    },
    getSubject(activity) {
      const context = activity.changes
        ? Object.keys(activity.changes?.after)
        : "";

      return {
        context: context.length ? context.join(", ") : context,
        type: activity.subject_type,
      };
    },
    activityTime(date) {
      return dayjs(date).fromNow();
    },
    locateProjectTask({ id }) {
      return this.project.tasks.find((task) => task.id === id);
    },
    async inviteMember() {
      try {
        this.inviteForm
          .post(this.$route("project.invite", this.project))
          .then((response) => {
            if (!Object.values(this.$page.errors).length) {
              setTimeout(() => {
                this.showInviteModal = false;
              }, 500);
              return;
            }
            this.$refs.inviteEmail.focus();
          });
      } catch (error) {
        //
      }
    },
    async deleteProject() {
      await this.$inertia.delete(this.$route("projects.destroy", this.project));
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
        },
        {
          preserveScroll: true
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
            // livewire.emit(`projectUpdated:${this.project.id}`, this.project)
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
        {
          preserveScroll: true,
        }
      );

      console.log(response, this.taskForm.hasErrors());
      this.taskForm.reset();
      this.showTaskModal = false;
    },

    async handleRefresh() {
      this.$inertia.reload({
            preserveScroll: true,
            preserveState: false
          })
      this.refreshProject = false
      console.log(this.project)
    }
  },
};
</script>

<style>
</style>