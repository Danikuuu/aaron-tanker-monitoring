<!-- resources/views/admin/create-admin.blade.php -->
@extends('admin.layout.app')

@section('title', 'Create New Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center">
                <div class="text-primary text-3xl font-bold">A</div>
            </div>
            <div>
                <h1 class="text-5xl font-bold tracking-wide">AARON</h1>
                <div class="flex items-center gap-2 mt-1">
                    <div class="text-sm">SINCE 2004</div>
                    <div class="flex gap-0.5">
                        @for($i = 0; $i < 12; $i++)
                            <div class="w-2 h-2 {{ $i % 2 == 0 ? 'bg-black' : 'bg-white border border-black' }}"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold">Create New Admin</h2>
        </div>

        <form method="POST" action="{{ route('admin.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="text" name="first_name" placeholder="First Name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <input type="text" name="last_name" placeholder="Last Name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <input type="email" name="email" placeholder="Email" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div class="relative">
                <input type="password" name="password" placeholder="Password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <div class="relative">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded-full hover:bg-[#ff4040] transition font-semibold text-lg">
                Create
            </button>
        </form>
    </div>
</div>
@endsection