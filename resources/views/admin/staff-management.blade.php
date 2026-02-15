<!-- resources/views/admin/staff-management.blade.php -->
@extends('admin.layout.app')

@section('title', 'Staff Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6">Staff Management</h2>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#FF5757] text-white">
                    <!-- <th class="px-4 py-3 text-left rounded-tl-lg">ID</th> -->
                    <th class="px-4 py-3 text-left">Full Name</th>
                    <th class="px-4 py-3 text-left">Email Address</th>
                    <th class="px-4 py-3 text-left">Registered</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                @forelse($staff as $member)
                <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                    <!-- <td class="px-4 py-3 text-gray-500 text-sm">{{ $member->id }}</td> -->
                    <td class="px-4 py-3 font-medium">
                        {{ $member->first_name }} {{ $member->last_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $member->email }}</td>
                    <td class="px-4 py-3 text-gray-500 text-sm">
                        {{ $member->created_at->format('M d, Y') }}
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-4 py-3">
                        @if($member->isPending())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        @elseif($member->isApproved())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Approved
                            </span>
                        @elseif($member->isBlocked())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Blocked
                            </span>
                        @endif
                    </td>

                    {{-- Action Dropdown --}}
                    <td class="px-4 py-3">
                        <div class="relative inline-block text-left" x-data="{ open: false }">

                            {{-- Trigger Button --}}
                            <button @click="open = !open" @click.outside="open = false"
                                    type="button"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white transition
                                        @if($member->isBlocked())  bg-gray-500  hover:bg-gray-600
                                        @elseif($member->isApproved()) bg-green-600 hover:bg-green-700
                                        @else                          bg-yellow-500 hover:bg-yellow-600
                                        @endif">
                                @if($member->isBlocked())
                                    Blocked
                                @elseif($member->isApproved())
                                    Approved
                                @else
                                    Pending
                                @endif
                                <svg class="w-4 h-4 transition-transform duration-200"
                                     :class="{ 'rotate-180': open }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-10 mt-1 w-40 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
                                 style="display: none;">

                                {{-- Approve --}}
                                @if($member->isApproved())
                                    <div class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 cursor-not-allowed bg-gray-50">
                                        <span class="w-2 h-2 rounded-full bg-green-300 inline-block"></span>
                                        Approve
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('admin.staff.approve', $member->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition">
                                            <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                {{-- Block --}}
                                @if($member->isBlocked())
                                    <div class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 cursor-not-allowed bg-gray-50">
                                        <span class="w-2 h-2 rounded-full bg-red-300 inline-block"></span>
                                        Block
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('admin.staff.block', $member->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                            <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                                            Block
                                        </button>
                                    </form>
                                @endif

                                {{-- Unblock (only if blocked) --}}
                                @if($member->isBlocked())
                                    <form method="POST" action="{{ route('admin.staff.unblock', $member->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                                            Unblock
                                        </button>
                                    </form>

                                    {{-- Delete (only if blocked) --}}
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST"
                                          action="{{ route('admin.staff.delete', $member->id) }}"
                                          onsubmit="return confirm('Permanently delete {{ $member->first_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        No staff members found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($staff->hasPages())
    <div class="flex items-center justify-center gap-4 mt-6">
        @if($staff->onFirstPage())
            <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Prev
            </span>
        @else
            <a href="{{ $staff->previousPageUrl() }}"
               class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Prev
            </a>
        @endif

        <span class="text-gray-600">
            Page {{ $staff->currentPage() }} of {{ $staff->lastPage() }}
        </span>

        @if($staff->hasMorePages())
            <a href="{{ $staff->nextPageUrl() }}"
               class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @else
            <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </span>
        @endif
    </div>
    @endif
</div>
@endsection