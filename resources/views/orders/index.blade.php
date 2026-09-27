@extends('layouts.app')

@section('title', ($tab === 'archived' ? 'Archived Orders' : 'Orders') . ' - CAPTAiN J POS')

@push('styles')
<style>
    .card-custom {
        background: #ffffff;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    }
    .badge-status {
        text-transform: capitalize;
        font-size: 0.8rem;
        padding: 0.35em 0.7em;
    }
    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.25rem;
    }
    .nav-tabs .nav-link.active {
        color: #dc2626;
        border-bottom-color: #dc2626;
        background: transparent;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom-color: #cbd5e1;
    }

    /* Clean payment method icon pill */
    .pay-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3em 0.65em;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }
    .pay-badge-gcash {
        background: #e8f0ff;
        color: #1a56db;
        border: 1px solid #c0d3ff;
    }
    .pay-badge-gcash .pay-icon {
        background: #1a56db;
        color: #fff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
    }
    .pay-badge-cash {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .pay-badge-cash .pay-icon {
        background: #16a34a;
        color: #fff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-receipt text-primary me-2"></i> 
                {{ $tab === 'archived' ? 'Archived Orders' : 'Recent Orders' }}
            </h3>
            <p class="text-secondary small m-0">View transactions, payment details, receipts, and order statuses.</p>
        </div>
    </div>

    <!-- Tabs Navigation (Active vs Archived) -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('orders.index', ['tab' => 'active']) }}" class="nav-link {{ $tab !== 'archived' ? 'active' : '' }}">
                <i class="fa-solid fa-list-check me-2"></i> Active Orders
                <span class="badge rounded-pill bg-danger-subtle text-danger ms-2">{{ $activeCount ?? $orders->total() }}</span>
            </a>
        </li>
        @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a href="{{ route('orders.index', ['tab' => 'archived']) }}" class="nav-link {{ $tab === 'archived' ? 'active' : '' }}">
                <i class="fa-solid fa-box-archive me-2"></i> Archived Orders
                <span class="badge rounded-pill bg-secondary ms-2">{{ $archivedCount ?? 0 }}</span>
            </a>
        </li>
        @endif
    </ul>

    <!-- Filters & Search Card -->
    <div class="card card-custom p-3 mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="orderSearch" class="form-control bg-light border-start-0" placeholder="Search orders by Customer, Staff, Payment, or Status...">
                </div>
            </div>
            <div class="col-12 col-md-6 text-md-end text-muted small">
                Showing <strong>{{ $orders->count() }}</strong> of <strong>{{ $orders->total() }}</strong> {{ $tab === 'archived' ? 'archived' : '' }} orders
            </div>
        </div>
    </div>

    <!-- Orders Data Table Card -->
    <div class="card card-custom p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem; min-width: 850px;">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                        <th>Cashier / Staff</th>
                        <th class="text-end" style="min-width: 270px;">
                            <div class="d-inline-flex align-items-center justify-content-end gap-2 flex-wrap">
                                <div id="headerBulkActions" class="d-none align-items-center gap-1 me-1">
                                    @if($tab !== 'archived')
                                    <button type="button" class="btn btn-xs btn-secondary py-1 px-2 fw-semibold" style="font-size: 0.75rem;" onclick="bulkArchiveSelected()" title="Archive selected orders">
                                        <i class="fa-solid fa-box-archive me-1"></i> Archive (<span class="selectedCountNum">0</span>)
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-xs btn-success text-white py-1 px-2 fw-semibold" style="font-size: 0.75rem;" onclick="bulkRestoreSelected()" title="Unarchive selected orders">
                                        <i class="fa-solid fa-box-open me-1"></i> Unarchive (<span class="selectedCountNum">0</span>)
                                    </button>
                                    <button type="button" class="btn btn-xs btn-danger text-white py-1 px-2 fw-semibold" style="font-size: 0.75rem;" onclick="bulkForceDeleteSelected()" title="Permanently delete selected orders">
                                        <i class="fa-solid fa-trash me-1"></i> Delete (<span class="selectedCountNum">0</span>)
                                    </button>
                                    @endif
                                </div>
                                <span class="fw-bold">Actions</span>
                                @if(auth()->user()->isAdmin())
                                <div class="form-check m-0 d-inline-flex align-items-center gap-1 ps-2 border-start" title="Select All Orders on page">
                                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input m-0" style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                    <label for="selectAllCheckbox" class="form-check-label small fw-semibold text-secondary user-select-none mb-0" style="cursor: pointer; font-size: 0.8rem;">Select All</label>
                                </div>
                                @endif
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="orderTable">
                    @forelse($orders as $o)
                    <tr id="order-row-{{ $o->id }}">
                        <td class="fw-bold text-secondary">#{{ $o->id }}</td>
                        <td class="fw-bold text-dark">{{ $o->customer_name ?: 'Walk-in' }}</td>
                        <td class="fw-bold text-primary fs-6">₱{{ number_format($o->total_amount, 2) }}</td>
                        <td>
                            @if(strtolower($o->payment_method) === 'gcash')
                                <span class="pay-badge pay-badge-gcash">
                                    <span class="pay-icon"><i class="fa-solid fa-mobile-screen-button"></i></span>
                                    GCash
                                </span>
                            @else
                                <span class="pay-badge pay-badge-cash">
                                    <span class="pay-icon"><i class="fa-solid fa-money-bill"></i></span>
                                    Cash
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                            {{ $o->status === 'completed' ? 'bg-success' : (in_array($o->status, ['voided', 'cancelled']) ? 'bg-secondary' : ($o->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger')) }} badge-status">
                            {{ ucfirst($o->status) }}
                        </span>
                        </td>
                        <td class="text-muted small">{{ $o->created_at->format('M j, Y • h:i A') }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $o->user->full_name ?? ($o->user->username ?? 'System') }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ ucfirst($o->user->role ?? 'Staff') }}</div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                <a href="{{ route('orders.show', $o->id) }}" class="btn btn-sm btn-light text-primary border" title="View Receipt">
                                    <i class="fa-solid fa-eye me-1"></i> View
                                </a>

                                @if($tab !== 'archived')
                                    <!-- Void Button: Available for BOTH Admin & Staff -->
                                    @if($o->status === 'completed')
                                    <button type="button"
                                        class="btn btn-sm btn-light text-warning border"
                                        title="Void Order"
                                        onclick="confirmVoid({{ $o->id }}, '{{ route('orders.void', $o->id) }}')"
                                    >
                                        <i class="fa-solid fa-rotate-left me-1"></i> Void
                                    </button>
                                    @endif

                                    <!-- Archive Button (Replaces Delete button): Admin Only -->
                                    @if(auth()->user()->isAdmin())
                                    <button type="button"
                                        class="btn btn-sm btn-light text-secondary border"
                                        title="Archive Order"
                                        onclick="confirmArchive({{ $o->id }}, '{{ route('orders.destroy', $o->id) }}')"
                                    >
                                        <i class="fa-solid fa-box-archive me-1"></i> Archive
                                    </button>
                                    @endif
                                @else
                                    <!-- Archived Orders Tab Actions: Admin Only -->
                                    @if(auth()->user()->isAdmin())
                                    <!-- Unarchive Button -->
                                    <button type="button"
                                        class="btn btn-sm btn-light text-success border"
                                        title="Unarchive Order"
                                        onclick="confirmUnarchive({{ $o->id }}, '{{ route('orders.restore', $o->id) }}')"
                                    >
                                        <i class="fa-solid fa-box-open me-1"></i> Unarchive
                                    </button>

                                    <!-- Permanent Delete Button -->
                                    <button type="button"
                                        class="btn btn-sm btn-light text-danger border"
                                        title="Permanently Delete Order"
                                        onclick="confirmForceDelete({{ $o->id }}, '{{ route('orders.force-delete', $o->id) }}')"
                                    >
                                        <i class="fa-solid fa-trash me-1"></i> Delete
                                    </button>
                                    @endif
                                @endif

                                @if(auth()->user()->isAdmin())
                                <div class="ms-2 ps-2 border-start d-inline-flex align-items-center" title="Select Order #{{ $o->id }}">
                                    <input type="checkbox" class="form-check-input order-select-box m-0" value="{{ $o->id }}" style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fa-regular fa-folder-open fs-2 mb-2 opacity-50"></i>
                            <p class="m-0">No {{ $tab === 'archived' ? 'archived' : '' }} orders found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->appends(['tab' => $tab])->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('orderSearch').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        document.querySelectorAll('#orderTable tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    function postForm(actionUrl, method = 'POST') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = actionUrl;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        if (method !== 'POST') {
            const mInput = document.createElement('input');
            mInput.type = 'hidden';
            mInput.name = '_method';
            mInput.value = method;
            form.appendChild(mInput);
        }

        document.body.appendChild(form);
        form.submit();
    }

    // Validation 1: Void Order
    function confirmVoid(orderId, voidUrl) {
        Swal.fire({
            title: 'Void Order #' + orderId + '?',
            html: 'This will <strong>restore the inventory stock</strong> of all items in this order and mark it as <strong>Voided</strong>.<br><br>Do you want to proceed?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-rotate-left me-1"></i> Yes, Void It',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postForm(voidUrl, 'POST');
            }
        });
    }

    // Validation 2: Archive Order
    function confirmArchive(orderId, archiveUrl) {
        Swal.fire({
            title: 'Archive Order #' + orderId + '?',
            text: 'This order will be moved to the Archived Orders list.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#64748b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-box-archive me-1"></i> Yes, Archive',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postForm(archiveUrl, 'DELETE');
            }
        });
    }

    // Validation 3: Unarchive Order
    function confirmUnarchive(orderId, restoreUrl) {
        Swal.fire({
            title: 'Unarchive Order #' + orderId + '?',
            text: 'This order will be restored back to the Active Orders list.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-box-open me-1"></i> Yes, Unarchive',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postForm(restoreUrl, 'POST');
            }
        });
    }

    // Validation 4: Permanent Delete Order
    function confirmForceDelete(orderId, deleteUrl) {
        Swal.fire({
            title: 'Permanently Delete Order #' + orderId + '?',
            html: '<strong class="text-danger">Warning:</strong> This action is permanent and cannot be undone!',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Yes, Delete Permanently',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postForm(deleteUrl, 'DELETE');
            }
        });
    }

    // ==========================================
    // Bulk / Batch Selection & Actions
    // ==========================================
    function getSelectedOrderIds() {
        const checked = document.querySelectorAll('.order-select-box:checked');
        return Array.from(checked).map(cb => cb.value);
    }

    function updateBulkUI() {
        const selected = getSelectedOrderIds();
        const count = selected.length;
        const visibleCheckboxes = Array.from(document.querySelectorAll('.order-select-box')).filter(cb => {
            const tr = cb.closest('tr');
            return tr && tr.style.display !== 'none';
        });
        const visibleCount = visibleCheckboxes.length;
        const visibleCheckedCount = visibleCheckboxes.filter(cb => cb.checked).length;

        const selectAllCb = document.getElementById('selectAllCheckbox');
        const headerActions = document.getElementById('headerBulkActions');

        if (selectAllCb) {
            selectAllCb.checked = visibleCount > 0 && visibleCheckedCount === visibleCount;
            selectAllCb.indeterminate = visibleCheckedCount > 0 && visibleCheckedCount < visibleCount;
        }

        // Update count labels in the actions header
        document.querySelectorAll('.selectedCountNum').forEach(el => el.textContent = count);

        // Toggle row highlight
        document.querySelectorAll('.order-select-box').forEach(cb => {
            const tr = cb.closest('tr');
            if (tr) {
                if (cb.checked) {
                    tr.classList.add('table-primary');
                } else {
                    tr.classList.remove('table-primary');
                }
            }
        });

        // Show / hide bulk actions beside the Actions heading
        if (count > 0) {
            if (headerActions) {
                headerActions.classList.remove('d-none');
                headerActions.classList.add('d-inline-flex');
            }
        } else {
            if (headerActions) {
                headerActions.classList.add('d-none');
                headerActions.classList.remove('d-inline-flex');
            }
        }
    }

    function postBulkForm(url, ids) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    function bulkArchiveSelected() {
        const ids = getSelectedOrderIds();
        if (ids.length === 0) {
            Swal.fire({ icon: 'info', title: 'No Orders Selected', text: 'Please select at least one order to archive.' });
            return;
        }

        Swal.fire({
            title: 'Archive ' + ids.length + ' Order' + (ids.length > 1 ? 's' : '') + '?',
            html: 'Are you sure you want to move <strong>' + ids.length + ' order' + (ids.length > 1 ? 's' : '') + '</strong> to the Archived Orders list?<br><br><span class="text-muted small">Orders: #' + ids.join(', #') + '</span>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#64748b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-box-archive me-1"></i> Yes, Archive (' + ids.length + ')',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postBulkForm('{{ route("orders.bulk-archive") }}', ids);
            }
        });
    }

    function bulkRestoreSelected() {
        const ids = getSelectedOrderIds();
        if (ids.length === 0) {
            Swal.fire({ icon: 'info', title: 'No Orders Selected', text: 'Please select at least one order to unarchive.' });
            return;
        }

        Swal.fire({
            title: 'Unarchive ' + ids.length + ' Order' + (ids.length > 1 ? 's' : '') + '?',
            html: 'Are you sure you want to restore <strong>' + ids.length + ' order' + (ids.length > 1 ? 's' : '') + '</strong> back to the Active Orders list?<br><br><span class="text-muted small">Orders: #' + ids.join(', #') + '</span>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-box-open me-1"></i> Yes, Unarchive (' + ids.length + ')',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postBulkForm('{{ route("orders.bulk-restore") }}', ids);
            }
        });
    }

    function bulkForceDeleteSelected() {
        const ids = getSelectedOrderIds();
        if (ids.length === 0) {
            Swal.fire({ icon: 'info', title: 'No Orders Selected', text: 'Please select at least one order to delete.' });
            return;
        }

        Swal.fire({
            title: 'Permanently Delete ' + ids.length + ' Order' + (ids.length > 1 ? 's' : '') + '?',
            html: '<strong class="text-danger">Warning:</strong> This action is permanent and cannot be undone!<br><br>Are you sure you want to permanently delete <strong>' + ids.length + ' order' + (ids.length > 1 ? 's' : '') + '</strong> from the database?<br><br><span class="text-muted small">Orders: #' + ids.join(', #') + '</span>',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Yes, Delete Permanently (' + ids.length + ')',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                postBulkForm('{{ route("orders.bulk-force-delete") }}', ids);
            }
        });
    }

    // Attach event listeners on load
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCb = document.getElementById('selectAllCheckbox');
        if (selectAllCb) {
            selectAllCb.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('#orderTable tr').forEach(row => {
                    if (row.style.display !== 'none') {
                        const cb = row.querySelector('.order-select-box');
                        if (cb) cb.checked = checked;
                    }
                });
                updateBulkUI();
            });
        }

        document.querySelectorAll('.order-select-box').forEach(cb => {
            cb.addEventListener('change', updateBulkUI);
        });
    });
</script>
@endpush
