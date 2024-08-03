<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
        }

        .header {
            text-align: left;
            margin-bottom: 20px;
        }

        .header img {
            height: 50px;
            float: right;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }

        .details, .salary-details, .deductions, .net-salary {
            margin-bottom: 20px;
        }

        .details h2, .salary-details h2, .deductions h2, .net-salary h2 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .details table, .salary-details table, .deductions table, .net-salary table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table td, .salary-details table td, .deductions table td, .net-salary table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .details table td:first-child, .salary-details table td:first-child, .deductions table td:first-child, .net-salary table td:first-child {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .sign {
            float: right;
            text-align: center;
        }

        .note {
            margin-top: 20px;
        }

        .confidential {
            text-align: center;
            font-size: 12px;
            color: #ff0000;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/mayatama-logo.png'))) }}"
            style="width: 100px; height: 100px"/>
        <div style="float: left">
            <p>{{ $companyProfile->name }} ({{ $slipSalary->user->branch->name }})<br>Internet Service Provider</p>
            <p>{{ \Carbon\Carbon::parse($slipSalary->period_start)->isoFormat('D MMMM Y') }}
                s/d {{ \Carbon\Carbon::parse($slipSalary->period_end)->isoFormat('D MMMM Y') }}
                <br>
                Senin, 01 Juli 2024</p>
        </div>
    </div>
    <div style="content: ''; clear: both; display: table"></div>
    <div class="details">
        <h2>Employee Details</h2>
        <table>
            <tr>
                <td>Nama</td>
                <td>{{ $slipSalary->user->name }}</td>
                <td>No. Induk Karyawan</td>
                <td>{{ $slipSalary->user->nip }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>{{ $slipSalary->user->roles[0]->name }}</td>
                <td>Status Karyawan</td>
                <td>{{ $slipSalary->user->jobInformation->contract_status }}</td>
            </tr>
        </table>
    </div>
    <div class="salary-details">
        <h2>Gaji Pokok</h2>
        <table>
            <tr>
                <td>Jumlah</td>
                <td>Rp {{ number_format($slipSalary->user->jobInformation->fixed_salary ?? '-') }}</td>
            </tr>
        </table>
        <h2>Tunjangan</h2>
        <table>
            <tr>
                <td>Jabatan</td>
                <td>Rp {{ number_format($slipSalary->positional_allowance)  ?? '-' }}</td>
            </tr>
            <tr>
                <td>Makan</td>
                <td>Rp {{ number_format($slipSalary->meal_allowance ) ?? '-' }}</td>
            </tr>
            <tr>
                <td>Transport</td>
                <td>Rp {{ number_format($slipSalary->transportation_allowance)  ?? '-' }}</td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td>Rp {{ number_format($slipSalary->overtime_allowance ) ?? '-' }}</td>
            </tr>
            <tr>
                <td>THR</td>
                <td>Rp -</td>
            </tr>
        </table>
        <h2>Bonus</h2>
        <table>
            <tr>
                <td>Penjualan</td>
                <td>Rp {{ number_format($slipSalary->sales_bonus) ?? '-' }}</td>
            </tr>
            <tr>
                <td>Project</td>
                <td>Rp {{ number_format($slipSalary->project_bonus) ?? '-' }}</td>
            </tr>
            <tr>
                <td>Lainnya</td>
                <td>Rp {{ number_format($slipSalary->other_bonus) ?? '-' }}</td>
            </tr>
        </table>
        <h2>Subtotal Tunjangan & Bonus</h2>
        <table>
            <tr>
                <td>Rp {{ number_format($allowanceAndBonusSum) }}</td>
            </tr>
        </table>
    </div>
    <div class="deductions">
        <h2>Iuran</h2>
        <table>
            <tr>
                <td>BPJS TEK</td>
                <td>Rp {{ number_format($slipSalary->bpjs_tek_dues) }}</td>
            </tr>
            <tr>
                <td>BPJS KES</td>
                <td>Rp {{ number_format($slipSalary->bpjs_kes_dues) }}</td>
            </tr>
            <tr>
                <td>Lainnya</td>
                <td>Rp -</td>
            </tr>
            <tr>
                <td>PPh 21</td>
                <td>Rp -</td>
            </tr>
        </table>
        <h2>Sub Total Potongan</h2>
        <table>
            <tr>
                <td>Rp{{ number_format($dues)}} </td>
            </tr>
        </table>
    </div>
    <div class="net-salary">
        <h2>Gaji Bersih Diterima</h2>
        <table>
            <tr>
                <td>Rp {{ number_format($netSalaryReceived) }}</td>
            </tr>
        </table>
    </div>
    <div class="sign" style="float: right">
        <p>Payroll Admin</p>
        <img
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/mayatama-logo.png'))) }}"
            style="width: 100px; height: 100px"/>
    </div>

</div>
</body>
</html>
