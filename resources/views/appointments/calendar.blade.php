@extends('layouts.app')
@section('title', 'Calendrier des rendez-vous')
@section('page-title', 'Calendrier des rendez-vous')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <div class="flex items-center gap-2">
        <a href="{{ route('appointments.index') }}" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Vue liste
        </a>
        @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'secretary']))
            <a href="{{ route('appointments.create') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau rendez-vous
            </a>
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 p-5">
    <div id="calendar"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const events = @json($appointments);
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            height: 650,
            events: events,
            eventClick: function(info) {
                window.location.href = info.event.url;
                info.jsEvent.preventDefault();
            },
            eventDisplay: 'block',
        });
        calendar.render();
    });
</script>
@endpush
