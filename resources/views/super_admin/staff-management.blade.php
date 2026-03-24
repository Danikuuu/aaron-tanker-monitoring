<!-- resources/views/admin/staff-management.blade.php -->
@extends('super_admin.layout.app')

@section('title', 'Staff Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6" x-data="{
    showModal: false,
    confirmModal: false,
    confirmAction: '',
    confirmMethod: 'POST',
    confirmTitle: '',
    confirmMessage: '',
    confirmBtnText: '',
    confirmBtnClass: '',

    openConfirm(action, method, title, message, btnText, btnClass) {
        this.confirmAction   = action;
        this.confirmMethod   = method;
        this.confirmTitle    = title;
        this.confirmMessage  = message;
        this.confirmBtnText  = btnText;
        this.confirmBtnClass = btnClass;
        this.confirmModal    = true;
    }
}">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Staff Management</h2>
        <button @click="showModal = true"
                class="inline-flex cursor-pointer items-center gap-2 bg-primary hover:bg-[#ff4040] text-white px-5 py-2 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </button>
    </div>

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

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-primary text-white">
                    <th class="px-4 py-3 text-left">Full Name</th>
                    <th class="px-4 py-3 text-left">Email Address</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Registered</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                @forelse($staff as $member)
                <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                    <td class="px-4 py-3 font-medium">
                        {{ $member->first_name }} {{ $member->last_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $member->email }}</td>

                    {{-- Role Badge --}}
                    <td class="px-4 py-3">
                        @if($member->role === 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Staff
                            </span>
                        @endif
                    </td>

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

                    {{-- Action Buttons --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">

                            @if($member->isPending())
                                {{-- Pending: Approve button --}}
                                <button type="button"
                                        @click="openConfirm(
                                            '{{ route('super_admin.staff.approve', $member->id) }}',
                                            'POST',
                                            'Approve Member',
                                            'Are you sure you want to approve <strong>{{ addslashes($member->first_name . ' ' . $member->last_name) }}</strong>? They will be able to log in to their account.',
                                            'Approve',
                                            'bg-green-600 hover:bg-green-700 text-white'
                                        )"
                                        class="inline-flex cursor-pointer items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>

                                <button type="button"
                                        @click="openConfirm(
                                            '{{ route('super_admin.staff.block', $member->id) }}',
                                            'POST',
                                            'Block Member',
                                            'Are you sure you want to block <strong>{{ addslashes($member->first_name . ' ' . $member->last_name) }}</strong>? They will no longer be able to log in.',
                                            'Block',
                                            'bg-red-600 hover:bg-red-700 text-white'
                                        )"
                                        class="inline-flex cursor-pointer items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Block
                                </button>

                            @elseif($member->isApproved())
                                {{-- Approved: Block button --}}
                                <button type="button"
                                        @click="openConfirm(
                                            '{{ route('super_admin.staff.block', $member->id) }}',
                                            'POST',
                                            'Block Member',
                                            'Are you sure you want to block <strong>{{ addslashes($member->first_name . ' ' . $member->last_name) }}</strong>? They will no longer be able to log in.',
                                            'Block',
                                            'bg-red-600 hover:bg-red-700 text-white'
                                        )"
                                        class="inline-flex cursor-pointer items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Block
                                </button>

                            @elseif($member->isBlocked())
                                {{-- Blocked: Unblock + Delete buttons --}}
                                <button type="button"
                                        @click="openConfirm(
                                            '{{ route('super_admin.staff.unblock', $member->id) }}',
                                            'POST',
                                            'Unblock Member',
                                            'Are you sure you want to unblock <strong>{{ addslashes($member->first_name . ' ' . $member->last_name) }}</strong>? They will be able to log in again.',
                                            'Unblock',
                                            'bg-blue-600 hover:bg-blue-700 text-white'
                                        )"
                                        class="inline-flex cursor-pointer items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    Unblock
                                </button>

                                <button type="button"
                                        @click="openConfirm(
                                            '{{ route('super_admin.staff.delete', $member->id) }}',
                                            'DELETE',
                                            'Delete Member',
                                            'Are you sure you want to <span class=\'font-semibold text-red-600\'>permanently delete</span> <strong>{{ addslashes($member->first_name . ' ' . $member->last_name) }}</strong>? This action cannot be undone.',
                                            'Delete',
                                            'bg-red-600 hover:bg-red-700 text-white'
                                        )"
                                        class="inline-flex cursor-pointer items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            @endif

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </span>
        @else
            <a href="{{ $staff->previousPageUrl() }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </a>
        @endif

        <span class="text-gray-600">
            Page {{ $staff->currentPage() }} of {{ $staff->lastPage() }}
        </span>

        @if($staff->hasMorePages())
            <a href="{{ $staff->nextPageUrl() }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
    @endif


    {{-- ── Confirmation Modal ──────────────────────────────────────────────── --}}
    <div x-show="confirmModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
         style="display: none;">

        <div @click.outside="confirmModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl w-full max-w-sm">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800" x-text="confirmTitle"></h3>
                <button @click="confirmModal = false"
                        class="text-gray-400 cursor-pointer hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600 leading-relaxed" x-html="confirmMessage"></p>
            </div>

            {{-- Modal Footer --}}
            <div class="flex gap-3 px-6 pb-5">
                <button type="button"
                        @click="confirmModal = false"
                        class="flex-1 px-4 cursor-pointer py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>

                <form id="confirmForm" method="POST" x-bind:action="confirmAction" class="flex-1">
                    @csrf
                    <template x-if="confirmMethod === 'DELETE'">
                        <input type="hidden" name="_method" value="DELETE">
                    </template>
                    <button type="submit"
                            :class="confirmBtnClass"
                            class="w-full px-4 cursor-pointer py-2 rounded-lg text-sm font-medium transition"
                            x-text="confirmBtnText">
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ── Add User Modal ─────────────────────────────────────────────────── --}}
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
         style="display: none;">

        <div @click.outside="showModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl w-full max-w-md">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Add New User</h3>
                <button @click="showModal = false"
                        class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form method="POST" action="{{ route('super_admin.staff.create') }}" class="px-6 py-5 space-y-4">
                @csrf

                {{-- Role toggle --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Role</label>
                    <div class="flex rounded-lg border border-gray-300 overflow-hidden text-sm">
                        <label class="flex-1 text-center">
                            <input type="radio" name="role" value="staff" class="sr-only peer" checked>
                            <span class="block py-2 cursor-pointer transition
                                         peer-checked:bg-primary peer-checked:text-white
                                         hover:bg-gray-50 text-gray-600">
                                Staff
                            </span>
                        </label>
                        <label class="flex-1 text-center border-l border-gray-300">
                            <input type="radio" name="role" value="admin" class="sr-only peer">
                            <span class="block py-2 cursor-pointer transition
                                         peer-checked:bg-primary peer-checked:text-white
                                         hover:bg-gray-50 text-gray-600">
                                Admin
                            </span>
                        </label>
                    </div>
                </div>

                {{-- First Name + Last Name --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                               placeholder="Juan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('first_name') border-red-400 @enderror">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                               placeholder="Dela Cruz"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('last_name') border-red-400 @enderror">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="juan@example.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Password</label>
                    <input type="password" name="password"
                           placeholder="Min. 8 characters"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           placeholder="Repeat password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                {{-- Footer --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal = false"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-primary hover:bg-[#ff4040] text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Re-open modal on validation error --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[x-data]');
        if (root && root._x_dataStack) {
            root._x_dataStack[0].showModal = true;
        }
    });
</script>
@endif

@endsection