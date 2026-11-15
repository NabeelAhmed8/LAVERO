@extends('admin.layout')

@section('title', 'Concierge Inquiries — LAVÉRO CMS')

@section('admin_content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-1">Private Appointments</span>
            <h1 class="font-serif text-3xl text-white font-light uppercase tracking-wide">
                Salon Fittings &amp; Concierge Messages ({{ $inquiries->total() }})
            </h1>
        </div>
    </div>

    <!-- Inquiries List -->
    <div class="rounded bg-[#0f0f0f] border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-black/40 text-neutral-400 uppercase tracking-widest border-b border-white/10">
                    <tr>
                        <th class="py-4 px-6 font-normal">Client</th>
                        <th class="py-4 px-6 font-normal">Preferred Salon</th>
                        <th class="py-4 px-6 font-normal">Message / Fitting Request</th>
                        <th class="py-4 px-6 font-normal">Received</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-neutral-300">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-white/[0.02] transition-colors align-top">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="text-white font-medium text-sm block">{{ $inquiry->name }}</span>
                                <a href="mailto:{{ $inquiry->email }}" class="text-[#c5a880] hover:underline text-[11px]">
                                    {{ $inquiry->email }}
                                </a>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider rounded bg-neutral-900 border border-white/10 text-white">
                                    {{ $inquiry->salon }}
                                </span>
                            </td>
                            <td class="py-4 px-6 max-w-md">
                                <p class="text-neutral-300 leading-relaxed">{{ $inquiry->message }}</p>
                            </td>
                            <td class="py-4 px-6 text-neutral-500 whitespace-nowrap">
                                {{ $inquiry->created_at->format('M d, Y') }}
                                <span class="block text-[10px] text-neutral-600">{{ $inquiry->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-neutral-500">
                                No concierge messages received yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($inquiries->hasPages())
            <div class="p-4 border-t border-white/10 flex justify-center">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
