
    @extends('student.layouts.layout')
    @section('student-subscription')
    
<div class="content pt-3 px-2">
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Subscription and Billing Section -->
        <div class="subscription-section mt-5">
            <h5>Subscription and Billing</h5>
            <h6>Current Plan</h6>

            @if ($subscriptions->isNotEmpty())
                @php
                    $current = $subscriptions->first();
                @endphp
                <div class="subscription-card mb-4 p-3 border rounded">
                    <div>
                        <h5>{{ $current->course->name ?? '-' }}</h5>
                        <h6>{{ round(\Carbon\Carbon::parse($current->start_date)->floatDiffInMonths(\Carbon\Carbon::parse($current->end_date))) }} Months Plan</h6>
                        <p>Next Billing: {{ \Carbon\Carbon::parse($current->end_date)->format('d-m-Y') }}</p>
                    </div>
                    <button class="btn btn-primary mt-2">Upgrade</button>
                </div>
            @else
                <p>No subscriptions found.</p>
            @endif

            <!-- Billing History -->
            <hr>
            <h6 class="text-dark fs-5 fw-medium">Billing History</h6>
            <div class="table-responsive">
                <table class="table table-bordered billing-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</td>
                                <td>{{ $subscription->course->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($subscription->status) {
                                            'cancelled' => 'text-white bg-danger px-2 py-1 rounded',
                                            'expired'   => 'text-white bg-secondary px-2 py-1 rounded',
                                            'active'    => 'text-white bg-success px-2 py-1 rounded',
                                        };
                                    @endphp
                                    <span class="{{ $badgeClass }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <!-- container -->
</div>

<style>
    /* Container styling */
    .subscription-section {
        background-color: #F0F7FF;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .subscription-section h5 {
        color: black;
        font-size: 24px;
        font-weight: 500;
    }

    .subscription-section h6 {
        color: black;
        font-size: 20px;
        font-weight: 500;
        margin-top: 20px;
    }

    /* Subscription card */
    .subscription-card {
        padding: 20px;
        background: #E6F3FF;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        display: flex;
        width: fit-content;
        gap: 50px;
        margin-bottom: 20px;
    }

    .subscription-card h5 {
        color: var(--Congress-Blue-900, #0F3E6B);
        font-size: 20px;
        font-weight: 500;
    }

    .subscription-card h6 {
        color: var(--Congress-Blue-900, #0F3E6B);
        font-size: 20px;
        font-weight: 400;
    }

    .subscription-card p {
        color: var(--Color-text, #474747);
        font-size: 12px;
        font-weight: 400;
    }

    .subscription-card button {
        height: 40px;
        width: auto;
        color: #000;
        background: white;
        border-radius: 5px !important;
        border: 1px rgba(0, 0, 0, 0.20) solid !important;
        padding: 5px 10px;
    }

    /* Status badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .status-completed {
        background-color: #FF9496 !important;
        color: #000 !important;
    }

    .status-active {
        background-color: #77DC8F !important;
        color: #000 !important;
    }

    .status-cancelled {
        background-color: #DC3545 !important;
        color: #fff !important;
    }

    .status-expired {
        background-color: #6C757D !important;
        color: #fff !important;
    }

    /* Table styling */
    table thead tr th {
        color: var(--Congress-Blue-900, #0F3E6B) !important;
        font-size: 16px !important;
        font-weight: 400 !important;
        padding-left: 0px !important;
    }

table tbody tr td {
    color: black !important; 
    font-size: 16px !important;
    font-weight: 500 !important;
    padding-left: 15px !important;   /* Adjust the left space */
}

</style>

@endsection