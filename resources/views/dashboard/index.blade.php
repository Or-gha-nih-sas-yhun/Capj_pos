@extends('layouts.app')

@section('title', 'Admin Dashboard - CAPTAiN J POS')

@push('styles')
<style>
    /* Card design system matching Sales Report */
    .card-custom {
        background: #ffffff;
        border: none;
        border-radius: 0.9rem;
        box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.05);
    }

    /* Welcome Alert */
    .welcome-msg {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        color: #fff;
        font-weight: 600;
        font-size: 0.82rem;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* KPI Tiles matching Sales Report */
    .kpi-tile {
        background: #ffffff;
        border-radius: 0.8rem;
        padding: 0.65rem 0.85rem;
        box-shadow: 0 3px 10px -2px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        height: 100%;
    }
    .kpi-tile:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -3px rgba(0, 0, 0, 0.08);
    }
    .kpi-tile-icon {
        width: 38px;
        height: 38px;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .kpi-tile-label {
        font-size: 0.64rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .kpi-tile-value {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.15;
    }
    .kpi-trend-up {
        color: #16a34a;
        font-weight: 700;
        font-size: 0.68rem;
    }
    .kpi-trend-down {
        color: #dc2626;
        font-weight: 700;
        font-size: 0.68rem;
    }
    .kpi-trend-flat {
        color: #94a3b8;
        font-weight: 700;
        font-size: 0.68rem;
    }

    /* Filter pill tabs matching Sales Report */
    .period-tab {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 0.35rem 0.85rem;
        border-radius: 2rem;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
    }
    .period-tab:hover {
        border-color: #ff1e1e;
        color: #ff1e1e;
    }
    .period-tab.active {
        background: linear-gradient(135deg, #ff1e1e 0%, #b30000 100%);
        border-color: #b30000;
        color: #ffffff;
        box-shadow: 0 4px 10px -3px rgba(241, 0, 0, 0.45);
    }

    /* Main Chart Container */
    .chart-container-card {
        background: #ffffff;
        border-radius: 0.9rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.05);
        padding: 1rem 1.15rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .report-chart-wrap {
        position: relative;
        height: 280px;
        width: 100%;
        flex: 1;
        min-height: 240px;
    }

    /* Payment Badges matching Sales Report */
    .pay-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25em 0.6em;
        border-radius: 999px;
        font-size: 0.74rem;
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
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.58rem;
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
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.58rem;
        flex-shrink: 0;
    }

    /* Compact panel tables */
    .insight-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.78rem;
    }
    .insight-table th {
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0.35rem 0.6rem;
        background-color: #f8fafc;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    .insight-table td {
        padding: 0.38rem 0.6rem;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }
    .insight-table tr:last-child td {
        border-bottom: none;
    }

    /* Sub-tabs in insights card */
    .sub-tab-btn {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 0.4rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .sub-tab-btn.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* Zoom Modal */
    .chart-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .chart-modal-content {
        background: #ffffff;
        border-radius: 1.25rem;
        width: 90vw;
        max-width: 1050px;
        height: 80vh;
        max-height: 680px;
        padding: 1.5rem;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
    }
    .chart-modal-close {
        position: absolute;
        top: 1rem;
        right: 1.25rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        line-height: 1;
        z-index: 10;
        transition: color 0.15s ease;
    }
    .chart-modal-close:hover {
        color: #0f172a;
    }

    /* Responsive height fitting */
    @media (max-width: 991.98px) {
        .report-chart-wrap { height: 250px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 py-1">

    @if(session('status') == 'login_success')
        <div id="welcome" class="welcome-msg mb-2">
            <span><i class="fa-solid fa-circle-check me-2"></i> Welcome back, <strong>{{ auth()->user()->full_name ?? auth()->user()->username }}</strong>!</span>
            <button type="button" class="btn-close btn-close-white btn-sm" onclick="this.parentElement.remove()"></button>
        </div>
    @endif

    <!-- Header matching Sales Report -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <div>
            <h4 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-chart-pie text-danger me-2"></i> Dashboard Overview
            </h4>
            <p class="text-secondary small m-0" style="font-size: 0.78rem;">
                Real-time business performance, sales analytics, and inventory metrics.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.76rem;" onclick="openDataModal()">
                <i class="fa-solid fa-table-cells text-danger"></i> All Tables
            </button>
            <span class="badge bg-dark-subtle text-dark fw-semibold px-3 py-2 rounded-pill" style="font-size: 0.76rem;">
                <i class="fa-regular fa-calendar me-1"></i>
                {{ $footer_date_start }} &ndash; {{ $footer_date_end }}
            </span>
        </div>
    </div>

    <!-- Filter Card Setup matching Sales Report -->
    <div class="card card-custom p-2 mb-2">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <!-- Chart / Period Filters -->
            <div class="d-flex flex-wrap gap-1" id="chartFilterTabs">
                <button type="button" class="period-tab active" data-chart="daily" onclick="switchChart('daily', this)">
                    <i class="fa-solid fa-calendar-day me-1"></i> Daily Sales
                </button>
                <button type="button" class="period-tab" data-chart="weekly" onclick="switchChart('weekly', this)">
                    <i class="fa-solid fa-calendar-week me-1"></i> Weekly Sales
                </button>
                <button type="button" class="period-tab" data-chart="monthly" onclick="switchChart('monthly', this)">
                    <i class="fa-solid fa-chart-line me-1"></i> Monthly Sales
                </button>
                <button type="button" class="period-tab" data-chart="product" onclick="switchChart('product', this)">
                    <i class="fa-solid fa-chart-column me-1"></i> Product Sales
                </button>
                <button type="button" class="period-tab" data-chart="share" onclick="switchChart('share', this)">
                    <i class="fa-solid fa-chart-pie me-1"></i> Sales Share
                </button>
                <button type="button" class="period-tab" data-chart="hours" onclick="switchChart('hours', this)">
                    <i class="fa-solid fa-fire me-1"></i> Peak Hours
                </button>
            </div>

            <!-- Date Form Filter -->
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex flex-wrap align-items-end gap-1">
                <div class="d-flex align-items-center gap-1">
                    <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">From</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; width: 130px;">
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">To</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; width: 130px;">
                </div>
                <button type="submit" class="btn btn-primary btn-sm fw-semibold px-2 py-1 shadow-sm" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-filter me-1"></i> Apply
                </button>
                @if(request('date_from') || request('date_to'))
                    <a href="{{ route('dashboard') }}" class="btn btn-light border btn-sm fw-semibold px-2 py-1" style="font-size: 0.75rem;">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <!-- KPI Row (6 Tiles matching Sales Report style) -->
    <div class="row g-2 mb-2">
        <!-- KPI 1: Sales Today -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-tile">
                <div class="kpi-tile-icon" style="background: #16a34a;">
                    <i class="fa-solid fa-peso-sign"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="kpi-tile-label">Sales Today</div>
                    <div class="kpi-tile-value">₱{{ number_format($sales_today, 2) }}</div>
                    <div class="mt-0">
                        @php
                            $today_pct = $sales_yesterday > 0 ? round((($sales_today - $sales_yesterday) / $sales_yesterday) * 100, 1) : 0;
                            $dir = $today_pct >= 0 ? 'kpi-trend-up' : 'kpi-trend-down';
                            $arrow = $today_pct >= 0 ? '&#9650;' : '&#9660;';
                        @endphp
                        <span class="{{ $dir }}">{!! $arrow !!} {{ abs($today_pct) }}%</span> <span class="text-muted" style="font-size: 0.65rem;">vs yest</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Monthly Revenue -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-tile">
                <div class="kpi-tile-icon" style="background: #2563eb;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="kpi-tile-label">Monthly Revenue</div>
                    <div class="kpi-tile-value">₱{{ number_format($monthly_revenue, 2) }}</div>
                    <div class="mt-0">
                        @php
                            $m_rev_pct = $monthly_revenue_prev > 0 ? round((($monthly_revenue - $monthly_revenue_prev) / $monthly_revenue_prev) * 100, 1) : 0;
                            $m_dir = $m_rev_pct >= 0 ? 'kpi-trend-up' : 'kpi-trend-down';
                            $m_arrow = $m_rev_pct >= 0 ? '&#9650;' : '&#9660;';
                        @endphp
                        <span class="{{ $m_dir }}">{!! $m_arrow !!} {{ abs($m_rev_pct) }}%</span> <span class="text-muted" style="font-size: 0.65rem;">vs last mo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Total Orders -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-tile">
                <div class="kpi-tile-icon" style="background: #f10000;">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="kpi-tile-label">Total Orders</div>
                    <div class="kpi-tile-value">{{ number_format($orders_this_month) }}</div>
                    <div class="mt-0">
                        @php
                            $o_pct = $orders_last_month > 0 ? round((($orders_this_month - $orders_last_month) / $orders_last_month) * 100, 1) : 0;
                            $o_dir = $o_pct >= 0 ? 'kpi-trend-up' : 'kpi-trend-down';
                            $o_arrow = $o_pct >= 0 ? '&#9650;' : '&#9660;';
                        @endphp
                        <span class="{{ $o_dir }}">{!! $o_arrow !!} {{ abs($o_pct) }}%</span> <span class="text-muted" style="font-size: 0.65rem;">vs last mo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Total Products -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-tile">
                <div class="kpi-tile-icon" style="background: #f59e0b;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="kpi-tile-label">Active Products</div>
                    <div class="kpi-tile-value">{{ $total_products }}</div>
                    <div class="mt-0">
                        @php
                            $a_pct = $total_products_prev > 0 ? round((($total_products - $total_products_prev) / $total_products_prev) * 100, 1) : 0;
                            $a_dir = $a_pct >= 0 ? 'kpi-trend-up' : 'kpi-trend-down';
                            $a_arrow = $a_pct >= 0 ? '&#9650;' : '&#9660;';
                        @endphp
                        <span class="{{ $a_dir }}">{!! $a_arrow !!} {{ abs($a_pct) }}%</span> <span class="text-muted" style="font-size: 0.65rem;">vs last mo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 5: Best Seller -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-tile">
                <div class="kpi-tile-icon" style="background: #8b5cf6;">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="kpi-tile-label">Best Seller</div>
                    <div class="kpi-tile-value text-truncate" style="font-size: 0.95rem;" title="{{ $best_product_name }}">
                        {{ $best_product_name }}
                    </div>
                    <div class="mt-0 text-muted" style="font-size: 0.66rem;">
                        <i class="fa-solid fa-bag-shopping me-1 text-primary"></i><strong>{{ number_format($best_product_count) }}</strong> orders
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 6: Sales Growth -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-tile">
                <div class="kpi-tile-icon" style="background: #06b6d4;">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="kpi-tile-label">Sales Growth</div>
                    <div class="kpi-tile-value" style="color: {{ $sales_growth >= 0 ? '#16a34a' : '#dc2626' }};">
                        {!! $sales_growth >= 0 ? '&#9650;' : '&#9660;' !!} {{ abs($sales_growth) }}%
                    </div>
                    <div class="mt-0 text-muted" style="font-size: 0.65rem;">vs last month</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area (Sales Reports layout: Left 8 cols chart, Right 4 cols Best Seller & Payment/Insights) -->
    <div class="row g-2">
        <!-- Interactive Main Chart Column -->
        <div class="col-12 col-xl-8">
            <div class="chart-container-card">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <div>
                        <h6 class="fw-bold m-0 text-dark" id="mainChartTitle">
                            <i class="fa-solid fa-calendar-day text-info me-2"></i>Daily Sales Trend
                        </h6>
                        <span class="text-muted" style="font-size: 0.72rem;" id="mainChartSubtitle">Revenue analytics and transaction trajectory.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="chartBadgesContainer" class="small">
                            <span class="badge bg-danger-subtle text-danger" id="badgeOne">Revenue</span>
                            <span class="badge bg-primary-subtle text-primary" id="badgeTwo">Sales (₱)</span>
                        </div>
                        <button type="button" class="btn btn-light btn-sm border py-0 px-2 text-muted" onclick="openActiveChartZoom()" title="Zoom Chart">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="report-chart-wrap">
                    <canvas id="activeMainChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Side: Best Seller & Payment Methods / Compact Panels -->
        <div class="col-12 col-xl-4">
            <!-- Best Seller Card (Matching Sales Report) -->
            <div class="card card-custom p-3 mb-2">
                <h6 class="fw-bold text-dark mb-2 d-flex align-items-center justify-content-between" style="font-size: 0.84rem;">
                    <span><i class="fa-solid fa-crown text-warning me-2"></i> Best Seller</span>
                    <span class="badge bg-warning-subtle text-warning-emphasis fw-bold" style="font-size: 0.65rem;">Top Performer</span>
                </h6>
                @if($best_product_name !== 'N/A')
                    <h5 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 1.05rem;" title="{{ $best_product_name }}">
                        {{ $best_product_name }}
                    </h5>
                    <div class="d-flex flex-wrap gap-3 mt-2 pt-1 border-top border-light-subtle">
                        <div>
                            <div class="kpi-tile-label">Qty Sold</div>
                            <div class="fw-bold fs-6 text-primary">{{ number_format($best_product_qty) }}</div>
                        </div>
                        <div>
                            <div class="kpi-tile-label">Revenue</div>
                            <div class="fw-bold fs-6 text-success">₱{{ number_format($best_product_revenue, 2) }}</div>
                        </div>
                        <div>
                            <div class="kpi-tile-label">Share</div>
                            <div class="fw-bold fs-6 text-dark">{{ $best_product_share }}%</div>
                        </div>
                    </div>
                @else
                    <p class="text-muted small m-0">No products sold in this period.</p>
                @endif
            </div>

            <!-- Payment Methods & Quick Insights Card (Matching Sales Report with interactive tabs) -->
            <div class="card card-custom p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-light-subtle">
                    <h6 class="fw-bold text-dark m-0" style="font-size: 0.84rem;">
                        <i class="fa-solid fa-credit-card text-info me-2"></i> <span id="insightCardTitle">Payment Methods</span>
                    </h6>
                    <div class="d-flex gap-1">
                        <button type="button" class="sub-tab-btn active" onclick="switchInsightTab('payments', this)">Payments</button>
                        <button type="button" class="sub-tab-btn" onclick="switchInsightTab('top5', this)">Top 5</button>
                        <button type="button" class="sub-tab-btn" onclick="switchInsightTab('least5', this)">Slow 5</button>
                        <button type="button" class="sub-tab-btn" onclick="switchInsightTab('peakdays', this)">Peak Days</button>
                    </div>
                </div>

                <!-- Pane 1: Payments -->
                <div id="insightPane-payments" class="insight-pane">
                    @forelse($payment_summary as $row)
                        <div class="d-flex justify-content-between align-items-center py-1 {{ !$loop->last ? 'border-bottom border-light-subtle' : '' }}">
                            <div>
                                @if(strtolower($row['method']) === 'gcash')
                                    <span class="pay-badge pay-badge-gcash">
                                        <span class="pay-icon"><i class="fa-solid fa-mobile-screen-button"></i></span>
                                        GCash
                                    </span>
                                @else
                                    <span class="pay-badge pay-badge-cash">
                                        <span class="pay-icon"><i class="fa-solid fa-money-bill"></i></span>
                                        {{ $row['method'] }}
                                    </span>
                                @endif
                                <div class="small text-muted ms-1" style="font-size: 0.68rem; display: inline-block;">
                                    {{ number_format($row['orders_count']) }} {{ Str::plural('order', $row['orders_count']) }}
                                </div>
                            </div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">₱{{ number_format($row['revenue'], 2) }}</div>
                        </div>
                    @empty
                        <p class="text-muted small m-0 py-2">No payments recorded.</p>
                    @endforelse
                </div>

                <!-- Pane 2: Top 5 Best Sellers -->
                <div id="insightPane-top5" class="insight-pane d-none" style="max-height: 140px; overflow-y: auto;">
                    <table class="insight-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top5_products as $i => $row)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold text-dark text-truncate" style="max-width: 110px;">{{ $row['name'] }}</td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($row['qty_sold']) }}</td>
                                    <td class="text-end fw-semibold text-success">₱{{ number_format($row['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-2">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pane 3: Slow 5 -->
                <div id="insightPane-least5" class="insight-pane d-none" style="max-height: 140px; overflow-y: auto;">
                    <table class="insight-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($least5_products as $i => $row)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold text-dark text-truncate" style="max-width: 110px;">{{ $row['name'] }}</td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($row['qty_sold']) }}</td>
                                    <td class="text-end fw-semibold text-success">₱{{ number_format($row['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-2">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pane 4: Peak Days -->
                <div id="insightPane-peakdays" class="insight-pane d-none" style="max-height: 140px; overflow-y: auto;">
                    <table class="insight-table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th class="text-end">Sales</th>
                                <th class="text-end">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peakday_rows as $row)
                                @php $pct = $total_all_sales > 0 ? round(($row['total'] / $total_all_sales) * 100, 1) : 0; @endphp
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $row['day_name'] }}</td>
                                    <td class="text-end fw-bold text-success">₱{{ number_format($row['total'], 2) }}</td>
                                    <td class="text-end fw-semibold text-muted">{{ number_format($pct, 1) }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-2">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Zoom Modal for Charts -->
<div class="chart-modal-overlay" id="chartModal" onclick="closeChartModal(event)">
    <div class="chart-modal-content" onclick="event.stopPropagation()">
        <span class="chart-modal-close" onclick="closeChartModal(event)">&times;</span>
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold text-dark m-0" id="modalChartTitle">Expanded Chart View</h5>
        </div>
        <div style="position: relative; flex: 1; width: 100%;">
            <canvas id="modalChartCanvas"></canvas>
        </div>
    </div>
</div>

<!-- All Tables Breakdown Modal (Preserves all 5 existing data tables!) -->
<div class="chart-modal-overlay" id="dataTablesModal" onclick="closeDataModal(event)">
    <div class="chart-modal-content" style="max-height: 85vh; overflow-y: auto;" onclick="event.stopPropagation()">
        <span class="chart-modal-close" onclick="closeDataModal(event)">&times;</span>
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-table-list text-danger me-2"></i> Detailed Performance Tables</h5>
        
        <div class="row g-3">
            <!-- Top 5 Best Sellers -->
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 bg-light">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i> Top 5 Best-Selling Products</h6>
                    <table class="table table-sm table-hover align-middle mb-0 bg-white rounded">
                        <thead class="table-light">
                            <tr><th>#</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Revenue</th></tr>
                        </thead>
                        <tbody>
                            @forelse($top5_products as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $row['name'] }}</td>
                                    <td class="text-end text-primary fw-bold">{{ number_format($row['qty_sold']) }}</td>
                                    <td class="text-end text-success fw-bold">₱{{ number_format($row['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top 5 Slow Moving -->
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 bg-light">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-arrow-trend-down text-danger me-1"></i> Top 5 Slow-Moving Products</h6>
                    <table class="table table-sm table-hover align-middle mb-0 bg-white rounded">
                        <thead class="table-light">
                            <tr><th>#</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Revenue</th></tr>
                        </thead>
                        <tbody>
                            @forelse($least5_products as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $row['name'] }}</td>
                                    <td class="text-end text-primary fw-bold">{{ number_format($row['qty_sold']) }}</td>
                                    <td class="text-end text-success fw-bold">₱{{ number_format($row['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sales Growth Summary -->
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 bg-light">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-chart-line text-primary me-1"></i> Monthly Growth Summary</h6>
                    <table class="table table-sm table-hover align-middle mb-0 bg-white rounded">
                        <thead class="table-light">
                            <tr><th>Period</th><th class="text-end">Sales</th><th class="text-end">Growth</th></tr>
                        </thead>
                        <tbody>
                            @forelse($growth_rows as $i => $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row['period'] }}</td>
                                    <td class="text-end text-success fw-bold">₱{{ number_format($row['total'], 2) }}</td>
                                    <td class="text-end">
                                        @if($i === 0)
                                            <span class="text-muted">&mdash;</span>
                                        @else
                                            @php
                                                $prev_total = $growth_rows[$i - 1]['total'];
                                                $g = $prev_total > 0 ? round((($row['total'] - $prev_total) / $prev_total) * 100, 2) : 0;
                                            @endphp
                                            <span class="{{ $g >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                {!! $g >= 0 ? '&#9650;' : '&#9660;' !!} {{ number_format(abs($g), 1) }}%
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Daily Sales Summary -->
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 bg-light">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-receipt text-info me-1"></i> Daily Summary Log</h6>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm table-hover align-middle mb-0 bg-white rounded">
                            <thead class="table-light">
                                <tr><th>Date</th><th class="text-end">Orders</th><th class="text-end">Revenue</th></tr>
                            </thead>
                            <tbody>
                                @forelse($daily_rows as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row['date_label'] }}</td>
                                        <td class="text-end text-primary fw-bold">{{ number_format($row['orders_count']) }}</td>
                                        <td class="text-end text-success fw-bold">₱{{ number_format($row['total_sales'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // All original datasets preserved 100%
    const products = {!! json_encode($products) !!};
    const sales = {!! json_encode($sales) !!};
    const salesPercent = {!! json_encode($sales_percent) !!};
    const months = {!! json_encode($months) !!};
    const monthSales = {!! json_encode($month_sales) !!};
    const dailyLabels = {!! json_encode($daily_labels) !!};
    const dailySales = {!! json_encode($daily_sales) !!};
    const weeklyLabels = {!! json_encode($weekly_labels) !!};
    const weeklySales = {!! json_encode($weekly_sales) !!};
    const hourLabels = {!! json_encode($hour_labels) !!};
    const hourSales = {!! json_encode($hour_sales) !!};

    function uniqueColors(count, offset = 0, alpha = 1) {
        const colors = [];
        const phi = 137.507764;
        for (let i = 0; i < count; i++) {
            const hue = Math.round((offset + i * phi) % 360);
            const sat = 75 + (i % 3) * 6;
            const light = 50 + (i % 2) * 8;
            colors.push(alpha === 1
                ? `hsl(${hue}, ${sat}%, ${light}%)`
                : `hsla(${hue}, ${sat}%, ${light}%, ${alpha})`);
        }
        return colors;
    }

    const peso = v => '₱' + Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Chart definitions for all 6 views
    const chartConfigs = {
        daily: {
            title: '<i class="fa-solid fa-calendar-day text-info me-2"></i>Daily Sales Trend',
            subtitle: 'Daily transaction revenue trajectory over the current timeframe.',
            badgeOne: 'Revenue',
            badgeTwo: 'Sales Trend',
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Sales (₱)',
                    data: dailySales,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.12)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0284c7',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Sales: ${peso(ctx.raw)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + Number(v).toLocaleString(), font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        },

        weekly: {
            title: '<i class="fa-solid fa-calendar-week text-danger me-2"></i>Weekly Sales Flow',
            subtitle: 'Weekly comparative flow and growth analysis.',
            badgeOne: 'Weekly',
            badgeTwo: 'Bridge Flow',
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Weekly Sales',
                    data: weeklySales,
                    backgroundColor: 'rgba(239, 68, 68, 0.75)',
                    borderColor: '#dc2626',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Sales: ${peso(ctx.raw)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + Number(v).toLocaleString(), font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        },

        monthly: {
            title: '<i class="fa-solid fa-chart-line text-primary me-2"></i>Monthly Sales Trend',
            subtitle: '12-Month revenue velocity and financial trajectory.',
            badgeOne: 'Monthly',
            badgeTwo: 'Revenue Growth',
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Sales (₱)',
                    data: monthSales,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.14)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.38,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2.5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Revenue: ${peso(ctx.raw)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + Number(v).toLocaleString(), font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        },

        product: {
            title: '<i class="fa-solid fa-chart-column text-success me-2"></i>Sales per Product',
            subtitle: 'Revenue generated across all individual product catalog items.',
            badgeOne: 'Products',
            badgeTwo: 'Catalog Sales',
            type: 'bar',
            data: {
                labels: products,
                datasets: [{
                    label: 'Sales (₱)',
                    data: sales,
                    backgroundColor: uniqueColors(products.length, 25, 0.85),
                    borderColor: uniqueColors(products.length, 25, 1),
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Sales: ${peso(ctx.raw)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + Number(v).toLocaleString(), font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 9 }, maxRotation: 45, minRotation: 0, autoSkip: true, maxTicksLimit: 14 },
                        grid: { display: false }
                    }
                }
            }
        },

        share: {
            title: '<i class="fa-solid fa-chart-pie text-warning me-2"></i>Product Sales Share',
            subtitle: 'Percentage market share contribution of each beverage / item.',
            badgeOne: 'Percentage',
            badgeTwo: 'Share Distribution',
            type: 'doughnut',
            data: {
                labels: products,
                datasets: [{
                    data: sales,
                    backgroundColor: uniqueColors(products.length, 195, 0.90),
                    borderColor: '#ffffff',
                    borderWidth: 2.5,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const label = ctx.label || '';
                                const value = ctx.raw || 0;
                                const pct = salesPercent[ctx.dataIndex] || 0;
                                return ` ${label}: ${peso(value)} (${pct}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            font: { size: 9.5, weight: '600' },
                            padding: 8,
                            generateLabels: function (chart) {
                                const data = chart.data;
                                return data.labels.slice(0, 10).map((label, i) => ({
                                    text: `${label} (${salesPercent[i] || 0}%)`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: 'transparent',
                                    index: i
                                }));
                            }
                        }
                    }
                }
            }
        },

        hours: {
            title: '<i class="fa-solid fa-fire text-danger me-2"></i>Peak Sales Hours',
            subtitle: 'Rush hour demand and hourly store activity patterns.',
            badgeOne: 'Hourly Flow',
            badgeTwo: 'Rush Hours',
            type: 'line',
            data: {
                labels: hourLabels,
                datasets: [{
                    label: 'Sales (₱)',
                    data: hourSales,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.18)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0284c7',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Sales: ${peso(ctx.raw)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + Number(v).toLocaleString(), font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        }
    };

    let activeChartInstance = null;
    let currentChartKey = 'daily';

    function renderChart(key) {
        const conf = chartConfigs[key];
        if (!conf) return;
        currentChartKey = key;

        // Update header & badges
        document.getElementById('mainChartTitle').innerHTML = conf.title;
        document.getElementById('mainChartSubtitle').innerText = conf.subtitle;
        document.getElementById('badgeOne').innerText = conf.badgeOne;
        document.getElementById('badgeTwo').innerText = conf.badgeTwo;

        const canvas = document.getElementById('activeMainChart');
        if (activeChartInstance) {
            activeChartInstance.destroy();
            activeChartInstance = null;
        }

        activeChartInstance = new Chart(canvas, {
            type: conf.type,
            data: JSON.parse(JSON.stringify(conf.data)),
            options: conf.options
        });
    }

    window.switchChart = function (key, btnEl) {
        document.querySelectorAll('#chartFilterTabs .period-tab').forEach(b => b.classList.remove('active'));
        if (btnEl) btnEl.classList.add('active');
        renderChart(key);
    };

    // Sub-tabs in right insights card
    window.switchInsightTab = function (paneKey, btnEl) {
        document.querySelectorAll('.sub-tab-btn').forEach(b => b.classList.remove('active'));
        if (btnEl) btnEl.classList.add('active');

        document.querySelectorAll('.insight-pane').forEach(p => p.classList.add('d-none'));
        const activePane = document.getElementById(`insightPane-${paneKey}`);
        if (activePane) activePane.classList.remove('d-none');

        const titleMap = {
            payments: 'Payment Methods',
            top5: 'Top 5 Best Sellers',
            least5: 'Slow-Moving Products',
            peakdays: 'Peak Sales Days'
        };
        const titleEl = document.getElementById('insightCardTitle');
        if (titleEl && titleMap[paneKey]) titleEl.innerText = titleMap[paneKey];
    };

    // Chart Zoom Modal
    let modalChartInstance = null;
    window.openActiveChartZoom = function () {
        const conf = chartConfigs[currentChartKey];
        if (!conf) return;

        const modal = document.getElementById('chartModal');
        const canvas = document.getElementById('modalChartCanvas');
        document.getElementById('modalChartTitle').innerHTML = conf.title;

        if (modalChartInstance) {
            modalChartInstance.destroy();
            modalChartInstance = null;
        }

        const modalOpts = Object.assign({}, conf.options, { responsive: true, maintainAspectRatio: false });
        modalChartInstance = new Chart(canvas, {
            type: conf.type,
            data: JSON.parse(JSON.stringify(conf.data)),
            options: modalOpts
        });

        modal.style.display = 'flex';
    };

    window.closeChartModal = function (e) {
        if (e && e.target !== e.currentTarget && e.target.className !== 'chart-modal-close') return;
        document.getElementById('chartModal').style.display = 'none';
        if (modalChartInstance) {
            modalChartInstance.destroy();
            modalChartInstance = null;
        }
    };

    // Tables Modal
    window.openDataModal = function () {
        document.getElementById('dataTablesModal').style.display = 'flex';
    };

    window.closeDataModal = function (e) {
        if (e && e.target !== e.currentTarget && e.target.className !== 'chart-modal-close') return;
        document.getElementById('dataTablesModal').style.display = 'none';
    };

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeChartModal(e);
            closeDataModal(e);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        renderChart('daily');
        setTimeout(() => {
            const welcome = document.getElementById('welcome');
            if (welcome) welcome.style.display = 'none';
        }, 3500);
    });
</script>
@endpush