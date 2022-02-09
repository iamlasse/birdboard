<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <jet-section-title v-if="hasHeader">
            <template #title><slot name="title"></slot></template>
            <template #description><slot name="description"></slot></template>
        </jet-section-title>

        <div class="mt-5 md:mt-0 " :class="{ 'md:col-span-3': !hasHeader}">
            <form @submit.prevent="$emit('submitted')">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <slot name="form"></slot>
                        </div>
                    </div>

                    <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6" v-if="hasActions">
                        <slot name="actions"></slot>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import JetSectionTitle from './SectionTitle'

    export default {
        components: {
            JetSectionTitle,
        },

    

        mounted() {
            // console.log(this.$slots)
        },

        computed: {
            hasHeader() {
                return !! (this.$slots.title || this.$slots.description)
            },
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
