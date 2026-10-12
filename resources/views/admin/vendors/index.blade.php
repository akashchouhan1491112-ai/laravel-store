@extends('admin.layout')

@section('title', 'Manage Vendors')

@section('admin-content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Vendor Management</h1>
            <p class="text-xs text-slate-600 mt-1">Review store applications, configure custom platform commission rates, and manage merchant status</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
        
        <!-- Filters -->
        <form action="{{ route('admin.vendors.index') }}" method="GET" class="flex flex-wrap items-center gap-3 pb-4 border-b border-slate-100">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by store or owner name..." class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                        <th class="pb-3">Store / Brand</th>
                        <th class="pb-3">Owner Contact</th>
                        <th class="pb-3">Products</th>
                        <th class="pb-3">Gross Sales</th>
                        <th class="pb-3">Commission %</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($stores as $st)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $st->logo_url }}" alt="{{ $st->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200 shrink-0">
                                    <div class="min-w-0">
                                        <div class="font-heading font-bold text-slate-900 truncate">{{ $st->name }}</div>
                                        <div class="text-[10px] text-slate-600 font-medium">{{ $st->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <div class="font-semibold text-slate-900">{{ $st->user->name }}</div>
                                <div class="text-[10px] text-slate-600">{{ $st->user->email }}</div>
                            </td>
                            <td class="py-3.5 font-bold text-slate-900">{{ $st->products_count }} items</td>
                            <td class="py-3.5 font-bold text-emerald-600">${{ number_format($st->total_sales, 2) }}</td>
                            <td class="py-3.5 font-bold text-indigo-600">{{ $st->commission_rate }}%</td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $st->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($st->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $st->status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.vendors.show', $st->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-bold text-[11px] transition">
                                        Manage & Commission →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $stores->links() }}
        </div>
    </div>

</div>
@endsection
