<div class="bg-white pb-4 px-4 rounded-md w-full">
    <div class="flex justify-between w-full pt-6 ">
    <p class="ml-3">Projects Table</p>
    <div class="flex items-center">
      <!-- Dropdown 1 -->
      <div x-data="{ showDropdown: @entangle('showDropdown').defer }" class="relative">
        <button
          @click="showDropdown = !showDropdown"
          class="relative z-10 block rounded p-2 focus:outline-none"
        >
          <svg
            class="h-6 w-6 text-gray-800"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
            />
          </svg>
        </button>

        <div
          x-show="showDropdown"
          @click="showDropdown = false"
          class="fixed inset-0 h-full w-full z-10"
        ></div>

        <div
          x-show="showDropdown"
          class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-20"
        >
          <a
            href="#"
            class="block px-4 py-2 text-sm text-gray-800 border-b hover:bg-gray-200"
            >small <span class="text-gray-600">(640x426)</span></a
          >
        </div>
      </div>
      <!-- Dropdown 2 -->
      <div x-data="{ dropdownOpen: false }" class="relative">
        <button
          @click="dropdownOpen = !dropdownOpen"
          class="relative z-10 block rounded p-2 focus:outline-none"
        >
          <svg
            class="h-6 w-6 text-gray-800"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
            />
          </svg>
        </button>

        <div
          x-show="dropdownOpen"
          @click="dropdownOpen = false"
          class="fixed inset-0 h-full w-full z-10"
        ></div>

        <div
          x-show="dropdownOpen"
          class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-20"
        >
          <a
            href="#"
            class="block px-4 py-2 text-sm text-gray-800 border-b hover:bg-gray-200"
            >small <span class="text-gray-600">(640x426)</span></a
          >
        </div>
      </div>
    </div>
  </div>
  <div class="w-full flex items-center justify-end px-2 mt-2">
    <div wire:loading wire:target="search" class="mr-4">
      Searching...
    </div>
    <div class="w-full sm:w-64 inline-block relative ">
      <input
        type="search"
        wire:model.debounce.500ms="search"
        name=""
        class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg"
        placeholder="Search"
      />

      <div
        class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300"
      >
        <svg
          class="fill-current h-3 w-3"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 511.999 511.999"
        >
          <path
            d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z"
          />
        </svg>
      </div>
    </div>
  </div>
  <div class="overflow-x-auto mt-6">
    <table
      class="table-auto border-collapse w-full"
      wire:loading.class="opacity-25"
    >
      <thead>
        <tr
          class="rounded-lg text-sm font-medium text-gray-700 text-left"
          style="font-size: 0.9674rem"
        >
          <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">
            Title
          </th>
          <th class="px-4 py-2 text-center " style="background-color:#f8f8f8">
            Completed
          </th>
          <th class="px-4 py-2 text-center " style="background-color:#f8f8f8">
            Incomplete
          </th>
          <!-- <th class="px-4 py-2 " style="background-color:#f8f8f8">Views</th> -->
        </tr>
      </thead>
      <tbody class="text-sm font-normal text-gray-700">
        @forelse($projects as $project)
        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
          <td class="px-4 py-4"><a href="{{ route( 'projects.show', $project ) }}">{{ $project->title }}</a></td>
          <td class="px-4 py-4 text-center">{{ $project->completed_tasks }}</td>
          <td class="px-4 py-4 text-center">
            {{ $project->incomplete_tasks }}
          </td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination mt-6">
    
  </div>
</div>

<style>
  thead tr th:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }
  thead tr th:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }

  tbody tr td:first-child {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 0px;
  }
  tbody tr td:last-child {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 0px;
  }
</style>
