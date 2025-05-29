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
    <h1>Projekt Report #{{ $application->id }}</h1>
    <p>Erstellt am: {{ now()->format('d.m.Y H:i:s') }}</p>

	<div class="section">
        <div class="section-title">Projekt Informationen</div>
		<div class="field">
            <span class="field-label">Projektname:</span> {{ $application->name }}
        </div>
        <div class="field">
            <span class="field-label">Projektstatus:</span> {{ $application->appl_status }}
        </div>
		<div class="field">
            <span class="field-label">Bereich:</span> {{ $application->bereich }}
        </div>
		<div class="field">
            <span class="field-label">Form:</span> {{ $application->form }}
        </div>
		<div class="field">
            <span class="field-label">Währung:</span> {{ $application->currency->abbreviation }}
        </div>
		<div class="field">
            <span class="field-label">Gewünschter Betrag:</span> {{ $application->req_amount }}
        </div>
		<div class="field">
            <span class="field-label">Ausgezahlter Betrag (Total):</span> {{ number_format($application->total_paid, 2) }} {{ $application->currency->abbreviation }}
        </div>
		<div class="field">
            <span class="field-label">Letzte Auszahlung:</span>
			{{ $application->last_payment_date ? $application->last_payment_date->format('d.m.Y') : '-' }}
        </div>
		<div class="field">
            <span class="field-label">Beginn des Projekts:</span>
            {{ $application->start_appl ? $application->start_appl->format('d.m.Y') : '-' }}
        </div>
		<div class="field">
            <span class="field-label">Ende des Projekts:</span>
            {{ $application->end_appl ? $application->end_appl->format('d.m.Y') : '-' }}
        </div>
        <div class="field">
            <span class="field-label">Erstellt am:</span>
            {{ $application->created_at->format('d.m.Y') }}
        </div>
        <div class="field">
            <span class="field-label">Eingereicht am:</span>
            {{ $application->submission_date ? $application->submission_date->format('d.m.Y H:i') : '-' }}
        </div>
		<div class="field">
            <span class="field-label">Genehmigt am:</span>
            {{ $application->approval_appl ? $application->approval_appl->format('d.m.Y') : '-' }}
        </div>
    </div>

	@if($application->payments && $application->payments->count() > 0)
	<div class="section">
		<div class="section-title">Zahlungen</div>
		<table>
			<thead>
				<tr>
					<th>Datum</th>
					<th>Betrag</th>
					<th>Notizen</th>
				</tr>
			</thead>
			<tbody>
				@foreach($application->payments->sortBy('payment_date') as $payment)
				<tr>
					<td>{{ $payment->payment_date->format('d.m.Y') }}</td>
					<td>{{ number_format($payment->amount, 2) }} {{ $application->currency->abbreviation }}</td>
					<td>{{ $payment->notes ?: '-' }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div class="field">
			<span class="field-label">Gesamtsumme ausgezahlt:</span> {{ number_format($application->total_paid, 2) }} {{ $application->currency->abbreviation }}
		</div>
	</div>
	@endif

	<div class="section">
        <div class="section-title">Persönliche Informationen</div>
        <div class="field">
            <span class="field-label">Name:</span> {{ $user->lastname }}, {{ $user->firstname }}
        </div>
        <div class="field">
            <span class="field-label">Email:</span> {{ $user->email }}
        </div>
		<div class="field">
            <span class="field-label">Geburtstag:</span>
			{{ $user->birthday ? $user->birthday->format('d.m.Y') : '-' }}
        </div>
		<div class="field">
            <span class="field-label">Telefon:</span> {{ $user->phone }}
        </div>
		@if ($user->type == App\Enums\Types::jur)
			<div class="field">
				<span class="field-label">Organisation:</span> {{ $user->name_inst }}
			</div>
			<div class="field">
				<span class="field-label">Email der Organisation:</span> {{ $user->email_inst }}
			</div>
		@endif

    </div>


    <div class="section">
        <div class="section-title">Hauptadresse</div>
        <div class="field">
            <span class="field-label">Strasse:</span> {{ $address->street }}
        </div>
        <div class="field">
            <span class="field-label">Hausnummer:</span> {{ $address->number }}
        </div>
        <div class="field">
            <span class="field-label">PLZ:</span> {{ $address->plz }}
        </div>
        <div class="field">
            <span class="field-label">Stadt:</span> {{ $address->town }}
        </div>
        <div class="field">
            <span class="field-label">Land:</span> {{ $address->country->name }}
        </div>
    </div>

    @if($abweichendeAddress)
    <div class="section">
        <div class="section-title">Wochenaufenthalt</div>
        <div class="field">
            <span class="field-label">Strasse:</span> {{ $address->street }}
        </div>
        <div class="field">
            <span class="field-label">Hausnummer:</span> {{ $address->number }}
        </div>
        <div class="field">
            <span class="field-label">PLZ:</span> {{ $address->plz }}
        </div>
        <div class="field">
            <span class="field-label">Stadt:</span> {{ $address->town }}
        </div>
        <div class="field">
            <span class="field-label">Land:</span> {{ $address->country->name }}
        </div>
    </div>
    @endif

    @if($aboardAddress)
    <div class="section">
        <div class="section-title">Adresse im Ausland</div>
        <div class="field">
            <span class="field-label">Strasse:</span> {{ $address->street }}
        </div>
        <div class="field">
            <span class="field-label">Hausnummer:</span> {{ $address->number }}
        </div>
        <div class="field">
            <span class="field-label">PLZ:</span> {{ $address->plz }}
        </div>
        <div class="field">
            <span class="field-label">Stadt:</span> {{ $address->town }}
        </div>
        <div class="field">
            <span class="field-label">Land:</span> {{ $address->country->name }}
        </div>
    </div>
    @endif

    @if($education)
    <div class="section">
        <div class="section-title">Ausbildung</div>
        <div class="field">
            <span class="field-label">Erstausbildung:</span> {{ $education->initial_education }}
        </div>
		<div class="field">
            <span class="field-label">Ausbildung:</span> {{ $education->education }}
        </div>
		<div class="field">
            <span class="field-label">Bezeichnung und Ort der Ausbildungsstätte:</span> {{ $education->name }}
        </div>
		<div class="field">
            <span class="field-label">Beabsichtigter Abschluss als:</span> {{ $education->final }}
        </div>
		<div class="field">
            <span class="field-label">Abschluss:</span> {{ $education->grade }}
        </div>
		<div class="field">
            <span class="field-label">ECTS-Punkte für das kommende Semester gemäss Beleg:</span> {{ $education->ects_points }}
        </div>
    </div>
    @endif

	@if($parents && $parents->count() > 0)
    <div class="section">
        <div class="section-title">Eltern</div>
        @foreach($parents as $parent)
		<div class="field">
            <span class="field-label">Elternteil:</span> {{ $parent->parent_type }}
        </div>
        <div class="field">
            <span class="field-label">Name:</span> {{ $parent->lastname }}, {{ $parent->firstname }}
        </div>
        <div class="field">
            <span class="field-label">Geburtsdatum:</span>
			{{ $parent->birthday ? $parent->birthday->format('d.m.Y') : '-' }}
        </div>
        <div class="field">
            <span class="field-label">Telefon:</span> {{ $parent->phone }}
        </div>
        <div class="field">
            <span class="field-label">Anschrift:</span> {{ $parent->address }}, {{ $parent->plz_ort }}
        </div>
		<div class="field">
            <span class="field-label">Wohnhaft seit:</span>
			{{ $parent->since ? $parent->since->format('d.m.Y') : '-' }}
        </div>
		<div class="field">
            <span class="field-label">Beruf:</span> {{ $parent->job }}
        </div>
        <div class="field">
            <span class="field-label">Arbeitgeber:</span> {{ $parent->employer }}
        </div>
        <div class="field">
            <span class="field-label">Arbeitsverhältnis:</span> {{ $parent->job_type }}
        </div>
        @endforeach
    </div>
    @endif

    @if($siblings && $siblings->count() > 0)
    <div class="section">
        <div class="section-title">Geschwister</div>
		@foreach($siblings as $sibling)
			<div class="field">
				<span class="field-label">Name:</span> {{ $sibling->lastname }}, {{ $sibling->firstname }}
			</div>
			<div class="field">
				<span class="field-label">Geburtstag:</span>
				{{ $sibling->birthday ? $sibling->birthday->format('d.m.Y') : '-' }}
			</div>
			<div class="field">
				<span class="field-label">Aufenthaltsadresse:</span> {{ $sibling->place_of_residence }}
			</div>
			<div class="field">
				<span class="field-label">Ausbildung/Berufstätigkeit (Schule/Lehre/Lehrjahr):</span> {{ $sibling->education }}
			</div>
			<div class="field">
				<span class="field-label">Abschlussjahr der Ausbildung:</span> {{ $sibling->graduation_year }}
			</div>
			<div class="field">
				<span class="field-label">Bezieht Ausbildungsbeiträge:</span> {{ $sibling->get_amount }}
			</div>
			<div class="field">
				<span class="field-label">Beziehende Stelle:</span> {{ $sibling->support_site }}
			</div>
        @endforeach
    </div>
    @endif

    @if($cost)
    <div class="section">
        <div class="section-title">Kosten</div>
		<div class="field">
			<span class="field-label">Semestergebühren:</span> {{ $cost->semester_fees }}
		</div>
		<div class="field">
			<span class="field-label">Übrige Gebühren:</span> {{ $cost->fees }}
		</div>
		<div class="field">
			<span class="field-label">Schulmaterialien/Lehrmittel:</span> {{ $cost->educational_material }}
		</div>
		<div class="field">
			<span class="field-label">Exkursionen/Schulverlegungen/Sprachaufenthalte:</span> {{ $cost->excursion }}
		</div>
		<div class="field">
			<span class="field-label">Reisespesen:</span> {{ $cost->travel_expenses }}
		</div>
		<div class="field">
			<span class="field-label">Anzahl unterhaltsberechtigte Kinder:</span> {{ $cost->number_of_children }}
		</div>

		<div class="section-title">Übrige Lebenshaltung</div>
		<div class="field">
			<span class="field-label">im Haushalt der Eltern:</span> {{ $cost->cost_of_living_with_parents }}
		</div>
		<div class="field">
			<span class="field-label">im eigenen Haushalt:</span> {{ $cost->cost_of_living_alone }}
		</div>
		<div class="field">
			<span class="field-label">im eigenen Haushalt Alleinerziehend:</span> {{ $cost->cost_of_living_single_parent }}
		</div>
		<div class="field">
			<span class="field-label">im eigenen Haushalt mit Partner:</span> {{ $cost->cost_of_living_with_partner }}
		</div>

        <div class="field">
            <span class="field-label">Total Cost:</span> {{ $cost->total_amount_costs }}
        </div>
    </div>
    @endif

    @if($costDarlehen && $costDarlehen->count() > 0)
    <div class="section">
        <div class="section-title">Kosten</div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Betrag</th>
                </tr>
            </thead>
            <tbody>
                @foreach($costDarlehen as $darlehen)
                <tr>
                    <td>{{ $darlehen->cost_name }}</td>
                    <td>{{ $darlehen->cost_amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($financing)
    <div class="section">
        <div class="section-title">Finanzierung</div>
		<div class="field">
            <span class="field-label">Eigenleistung vom Bewerber selbst:</span> {{ $financing->personal_contribution }}
        </div>
		<div class="field">
            <span class="field-label">Einkommen netto des Ehe- / Lebenspartners minus Freibetrag:</span> {{ $financing->netto_income }}
        </div>
		<div class="field">
            <span class="field-label">Eigenes Vermögen (Vermögen bei erster Gesuchstellung) :</span> {{ $financing->assets }}
        </div>
		<div class="field">
            <span class="field-label">Zumutbare Elternleistung gem. Berechnung:</span> {{ $financing->scholarship }}
        </div>
		<div class="field">
            <span class="field-label">Anderweitige Einkünfte:</span> {{ $financing->other_income }}
        </div>
		<div class="field">
            <span class="field-label">Auszahlende Stelle der anderweitige Einkünfte:</span> {{ $financing->income_where }}
        </div>
		<div class="field">
            <span class="field-label">Begünstigter der anderweitige Einkünfte:</span> {{ $financing->income_who }}
        </div>

        <div class="field">
            <span class="field-label">Total Financing:</span> {{ $financing->total_amount_financing }}
        </div>
    </div>
    @endif

    @if($financingOrganisation && $financingOrganisation->count() > 0)
    <div class="section">
        <div class="section-title">Finanzierung Organisation</div>
        <table>
            <thead>
                <tr>
                    <th>Organization</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($financingOrganisation as $financing)
                <tr>
                    <td>{{ $financing->financing_name }}</td>
                    <td>{{ $financing->financing_amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

	@if($messages && $messages->count() > 0)
<div class="section">
    <div class="section-title">Kommunikation</div>
    <table>
        <thead>
            <tr>
                <th>Datum</th>
                <th>Von</th>
                <th>Nachricht</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages->sortBy('created_at') as $message)
            <tr>
                <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $message->user->firstname }} {{ $message->user->lastname }}</td>
                <td>{{ $message->body }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


</body>
</html>
