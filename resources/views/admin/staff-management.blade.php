<!-- resources/views/admin/staff-management.blade.php -->
@extends('admin.layout.app')

@section('title', 'Staff Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6">Staff Management</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#FF5757] text-white">
                    <th class="px-4 py-3 text-left rounded-tl-lg">ID</th>
                    <th class="px-4 py-3 text-left">Full Name</th>
                    <th class="px-4 py-3 text-left">Email address</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3">1</td>
                    <td class="px-4 py-3">Lorem Ipsum</td>
                    <td class="px-4 py-3">loremipsum123@ipsum.com</td>
                    <td class="px-4 py-3">
                        <span class="text-yellow-600 font-semibold">New</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="relative inline-block">
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                                Approve
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3">2</td>
                    <td class="px-4 py-3">Lorem Ipsum</td>
                    <td class="px-4 py-3">loremipsum123@ipsum.com</td>
                    <td class="px-4 py-3">
                        <span class="text-green-600 font-semibold">Approved</span>
                    </td>
                    <td class="px-4 py-3">
                        <button class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                            Block
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3">3</td>
                    <td class="px-4 py-3">Lorem Ipsum</td>
                    <td class="px-4 py-3">loremipsum123@ipsum.com</td>
                    <td class="px-4 py-3">
                        <span class="text-green-600 font-semibold">Approved</span>
                    </td>
                    <td class="px-4 py-3">
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            Approve
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3">4</td>
                    <td class="px-4 py-3">Lorem Ipsum</td>
                    <td class="px-4 py-3">loremipsum123@ipsum.com</td>
                    <td class="px-4 py-3">
                        <span class="text-green-600 font-semibold">Approved</span>
                    </td>
                    <td class="px-4 py-3">
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            Approve
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3">5</td>
                    <td class="px-4 py-3">Lorem Ipsum</td>
                    <td class="px-4 py-3">loremipsum123@ipsum.com</td>
                    <td class="px-4 py-3">
                        <span class="text-red-600 font-semibold">Blocked</span>
                    </td>
                    <td class="px-4 py-3">
                        <button class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                            Delete
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-center gap-4 mt-6">
        <button class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Prev
        </button>
        <span class="text-gray-600">Page 1 of 4</span>
        <button class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
            Next
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <div class="flex justify-center mt-8">
        <button class="bg-[#FF5757] text-white px-8 py-3 rounded-full hover:bg-[#ff4040] transition flex items-center gap-3 text-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Admin
        </button>
    </div>
</div>
@endsection