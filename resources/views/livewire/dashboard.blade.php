<div class="py-12">
  <div
    class="alert-wrapper"
    x-data="{showUpdates: @entangle('showNotification') }"
  >
    <div class="test bg-green-200 text-gray-700 p-10" x-show="showUpdates">
      <div class="span">Project Updated {{ $project->id }}</div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 md:w-full">
    <h1 class="text-6xl font-black text-white">
      <span
        class="from-teal-400 to-teal-900 text-transparent bg-gradient-to-tr bg-clip-text"
        >Projects</span
      >
    </h1>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <livewire:data-table />
    </div>
  </div>
</div>
