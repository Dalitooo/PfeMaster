@extends('layouts.app')
@section('title', 'Edit Staff')
@section('page-title', 'Edit Staff Member')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back
    </a>
    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                    <select name="role" id="role_select" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['admin','doctor','secretary','supplier'] as $r)
                            <option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label><input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">New Password <span class="text-slate-400">(leave blank to keep)</span></label><input type="password" name="password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label><input type="password" name="password_confirmation" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="flex items-center gap-2 mt-1">
                    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $user->is_active)) class="rounded">
                    <label for="is_active" class="text-sm text-slate-700">Active account</label>
                </div>
            </div>
        </div>

        <div id="doctor-fields" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 {{ old('role', $user->role) === 'doctor' ? '' : 'hidden' }}">
            <h3 class="font-semibold text-slate-800">Doctor Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Specialization</label><input type="text" name="specialization" value="{{ old('specialization', $user->doctorProfile?->specialization) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">License Number</label><input type="text" name="license_number" value="{{ old('license_number', $user->doctorProfile?->license_number) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Save Changes</button>
            <a href="{{ route('users.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Cancel</a>
        </div>
    </form>
</div>
@push('scripts')
<script>document.getElementById('role_select').addEventListener('change', function() { document.getElementById('doctor-fields').classList.toggle('hidden', this.value !== 'doctor'); });</script>
@endpush
@endsection
