<!DOCTYPE html>
<html>
<head>
    <title>E-ticket - Email Notification</title>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td style="text-align: right;">
                <h2 style="margin: 0;">E-ticket</h2>
                <p style="margin: 0; font-size: 12px;">Email Notification</p>
            </td>
        </tr>
    </table>

    <hr style="border-top: 1px solid #ccc;">

    <h3>{{ $details['subject'] }}</h3>
    <p>{{ $details['body'] }}</p>
    <?php

        if ($details['NIK'] != null) {
    ?>
            <p>NIK = <b>{{ $details['nik'] }}</b></p>
            <p>Password = <b>{{ $details['password'] }}</b></p>
    <?php
        }
    
    ?>

    <hr style="border-top: 1px solid #ccc;">

    <p style="font-size: 12px;">
        Sent by: E-Ticket - MT HCIS<br>
        Altira Office Tower kav. 85 Jl. Yos Sudarso, Tanjung Priok District, Kota Jakarta Utara, 14350, Indonesia<br>
        mthcis@outlook.com
    </p>

    <p style="font-size: 12px; color: #999;">
        *This is an automated notification. Please do not reply to this email.
    </p>
</body>
</html>
