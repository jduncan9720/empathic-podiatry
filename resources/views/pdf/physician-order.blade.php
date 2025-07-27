<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Physician Order for Podiatry Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo {
            width: 30px;
            height: 30px;
            background-color: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 14px;
        }
        
        .title-section {
            text-align: right;
        }
        
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .date {
            font-size: 12px;
            margin-top: 5px;
        }
        
        .patient-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .patient-table th,
        .patient-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        .patient-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .facility-cell {
            width: 25%;
        }
        
        .reason-cell {
            width: 75%;
            text-align: center;
            font-weight: bold;
        }
        
        .name-cell {
            width: 25%;
        }
        
        .diagnoses-cell {
            width: 35%;
        }
        
        .status-cell {
            width: 13.33%;
            text-align: center;
        }
        
        .diagnosis-item {
            margin-bottom: 3px;
        }
        
        .checkmark {
            font-weight: bold;
            color: #000;
        }
        
        .footer-section {
            margin-top: 30px;
        }
        
        .certification-text {
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            margin: 0 5px;
        }
        
        .signature-section {
            margin: 20px 0;
        }
        
        .signature-row {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        
        .signature-label {
            margin-right: 10px;
            min-width: 120px;
        }
        
        .role-options {
            margin-left: 20px;
            font-weight: bold;
        }
        
        .date-section {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">👣</div>
            <div class="company-name">Empathic Podiatry</div>
        </div>
        <div class="title-section">
            <h1 class="title">Physician Order for Podiatry Services</h1>
            <div class="date">{{ date('m/d/Y') }}</div>
        </div>
    </div>

    <!-- Patient Table -->
    <table class="patient-table">
        <thead>
            <tr>
                <th class="facility-cell">Facility: {{ $facilityName ?? 'Spring Creek' }}</th>
                <th class="reason-cell" colspan="4">Reason not to be seen</th>
            </tr>
            <tr>
                <th class="name-cell">Name</th>
                <th class="diagnoses-cell">Diagnoses</th>
                <th class="status-cell">Deceased</th>
                <th class="status-cell">Discharged</th>
                <th class="status-cell">Other</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($patients) && count($patients) > 0)
                @foreach($patients as $patient)
                    <tr>
                        <td class="name-cell">
                            <strong>{{ $patient->name }}</strong>
                        </td>
                        <td class="diagnoses-cell">
                            @if(isset($patient->diagnoses))
                                @foreach($patient->diagnoses as $diagnosis)
                                    <div class="diagnosis-item">{{ $diagnosis }}</div>
                                @endforeach
                            @else
                                <!-- Default diagnoses if none provided -->
                                <div class="diagnosis-item">Onycomicosis B35.1</div>
                                <div class="diagnosis-item">Generalized Atherosclerosis I70.91</div>
                            @endif
                        </td>
                        <td class="status-cell">
                            @if($patient->status === 'deceased')
                                <span class="checkmark">✓</span>
                            @endif
                        </td>
                        <td class="status-cell">
                            @if($patient->status === 'discharged')
                                <span class="checkmark">✓</span>
                            @endif
                        </td>
                        <td class="status-cell">
                            @if($patient->status === 'refused')
                                <span class="checkmark">✓</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        No physician consent patients found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer Sections -->
    <div class="footer-section">
        <!-- PCP/Medical Director Section -->
        <div class="certification-text">
            I certify that I am responsible for the care of the above mentioned patients. I have ordered foot care services for the above mentioned patients.
        </div>
        
        <div class="signature-section">
            <div class="signature-row">
                <span class="signature-label">Signature:</span>
                <span class="signature-line"></span>
                <span class="role-options">MD DO NP PA</span>
            </div>
            <div class="signature-row">
                <span class="signature-label">Name:</span>
                <span class="signature-line"></span>
            </div>
        </div>

        <!-- Nurses Section -->
        <div class="certification-text">
            I attest that the above mentioned patients' primary provider, who is responsible for their overall care, has ordered foot care services. This constitutes a telephone order and must be authenticated by the provider's signature within a reasonable period of time following issuing of the order and retained in the patient's chart.
        </div>
        
        <div class="signature-section">
            <div class="signature-row">
                <span class="signature-label">Physician's Name:</span>
                <span class="signature-line"></span>
                <span class="role-options">MD DO NP PA</span>
            </div>
            <div class="signature-row">
                <span class="signature-label">Signature of Nurse:</span>
                <span class="signature-line"></span>
                <span class="role-options">RN LPN LVN</span>
            </div>
            <div class="signature-row">
                <span class="signature-label">Nurse's Printed Name:</span>
                <span class="signature-line"></span>
            </div>
            <div class="signature-row">
                <span class="signature-label">Date:</span>
                <span class="signature-line"></span>
                <span style="margin-left: 10px;">{{ date('m/d/Y') }}</span>
            </div>
        </div>
    </div>
</body>
</html> 