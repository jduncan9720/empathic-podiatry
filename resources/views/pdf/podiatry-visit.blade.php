<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Podiatry Visit</title>
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
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .contact-info {
            margin-top: 10px;
            font-size: 11px;
        }
        
        .facility-info {
            margin-top: 15px;
            font-size: 11px;
        }
        
        .facility-info div {
            margin-bottom: 3px;
        }
        
        .date-section {
            margin-top: 10px;
            font-size: 11px;
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
            background-color: #e6f3ff;
            font-weight: bold;
            text-align: center;
        }
        
        .patient-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .patient-table tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        .index-cell {
            width: 8%;
            text-align: center;
            font-weight: bold;
        }
        
        .name-cell {
            width: 35%;
        }
        
        .room-cell {
            width: 20%;
        }
        
        .comment-cell {
            width: 37%;
        }
        
        .patient-name {
            font-weight: normal;
        }
        
        .room-number {
            font-weight: normal;
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
            <h1 class="title">Podiatry Visit</h1>
            <div class="contact-info">
                Contact: {{ $facilityContact ?? '' }}
            </div>
        </div>
    </div>

    <!-- Facility Information -->
    <div class="facility-info">
        <div><strong>Facility:</strong> {{ $facilityName ?? 'Spring Creek' }}</div>
        <div><strong>Address:</strong> 4600 Highland Drive, Millcreek, UT</div>
        <div><strong>Phone:</strong> 801-272-1892</div>
    </div>

    <!-- Date Section -->
    <div class="date-section">
        <strong>Date:</strong> {{ date('m/d/Y') }}
    </div>

    <!-- Patient Table -->
    <table class="patient-table">
        <thead>
            <tr>
                <th class="index-cell">#</th>
                <th class="name-cell">Name</th>
                <th class="room-cell">Room</th>
                <th class="comment-cell">Comment</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($patients) && count($patients) > 0)
                @foreach($patients as $index => $patient)
                    <tr>
                        <td class="index-cell">{{ $index + 1 }}</td>
                        <td class="name-cell">
                            <span class="patient-name">{{ $patient->name }}</span>
                        </td>
                        <td class="room-cell">
                            <span class="room-number">{{ $patient->room_number ?? '' }}</span>
                        </td>
                        <td class="comment-cell">
                            <!-- Empty for comments -->
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">
                        No patients found that need to be seen
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html> 