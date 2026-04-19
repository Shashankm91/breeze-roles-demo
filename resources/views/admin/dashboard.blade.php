@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
                <p>Welcome, {{ Auth::user()->name }}!</p>
                <p>Your role: <span class="font-semibold text-blue-600">{{ Auth::user()->roles->pluck('name')->implode(', ') }}</span></p>

                <div class="mt-6">
                    <h2 class="text-xl font-semibold">Quick Actions</h2>
                    <ul class="list-disc list-inside mt-2">
                        <li><a href="{{ route('admin.users') }}" class="text-blue-600 hover:underline">Manage Users</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
