<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Report - {{ $application->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .field {
            margin-bottom: 8px;
        }
        .field-label {
            font-weight: bold;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h1>Application Report #{{ $application->id }}</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="field">
            <span class="field-label">Name:</span> {{ $user->name }}
        </div>
        <div class="field">
            <span class="field-label">Email:</span> {{ $user->email }}
        </div>
        <div class="field">
            <span class="field-label">Application Status:</span> {{ $application->appl_status }}
        </div>
        <div class="field">
            <span class="field-label">Created At:</span> {{ $application->created_at->format('Y-m-d') }}
        </div>
    </div>

    @if($education)
    <div class="section">
        <div class="section-title">Education Details</div>
        <div class="field">
            <span class="field-label">Institution:</span> {{ $education->institution }}
        </div>
        <div class="field">
            <span class="field-label">Study Program:</span> {{ $education->study_program }}
        </div>
        <div class="field">
            <span class="field-label">Duration:</span> {{ $education->duration }}
        </div>
    </div>
    @endif

    @if($cost)
    <div class="section">
        <div class="section-title">Costs</div>
        <div class="field">
            <span class="field-label">Total Cost:</span> {{ $cost->total_cost }}
        </div>
    </div>
    @endif

    @if($costDarlehen && $costDarlehen->count() > 0)
    <div class="section">
        <div class="section-title">Cost Darlehen</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($costDarlehen as $darlehen)
                <tr>
                    <td>{{ $darlehen->cost_description }}</td>
                    <td>{{ $darlehen->cost_amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($financing)
    <div class="section">
        <div class="section-title">Financing</div>
        <div class="field">
            <span class="field-label">Total Financing:</span> {{ $financing->total_financing }}
        </div>
    </div>
    @endif

    @if($financingOrganisation && $financingOrganisation->count() > 0)
    <div class="section">
        <div class="section-title">Financing Organizations</div>
        <table>
            <thead>
                <tr>
                    <th>Organization</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($financingOrganisation as $org)
                <tr>
                    <td>{{ $org->financing_organisation }}</td>
                    <td>{{ $org->financing_amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($enclosure)
    <div class="section">
        <div class="section-title">Submitted Documents</div>
        <ul>
            @if($enclosure->cv)
                <li>CV</li>
            @endif
            @if($enclosure->motivation_letter)
                <li>Motivation Letter</li>
            @endif
            @if($enclosure->diplomas)
                <li>Diplomas</li>
            @endif
            @if($enclosure->language_certificates)
                <li>Language Certificates</li>
            @endif
            @if($enclosure->acceptance_letter)
                <li>Acceptance Letter</li>
            @endif
            @if($enclosure->registration_confirmation)
                <li>Registration Confirmation</li>
            @endif
            @if($enclosure->budget_plan)
                <li>Budget Plan</li>
            @endif
            @if($enclosure->transcript_records)
                <li>Transcript Records</li>
            @endif
        </ul>
    </div>
    @endif
</body>
</html>
