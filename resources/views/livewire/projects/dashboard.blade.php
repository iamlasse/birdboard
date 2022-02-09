<div class="py-12">
  <div
    class="alert-wrapper"
    x-data="{showUpdates:false }"
  >
    <div class="test bg-green-200 text-gray-700 p-10" x-show="showUpdates">
      <div class="span">Project Updated</div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 md:w-full">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <livewire:data-table />
    </div>
  </div>
</div>
