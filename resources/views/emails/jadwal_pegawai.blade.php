<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifikasi Jadwal</title>
</head>
<body>

    <h2>Halo, {{ $user->name }}</h2>

    <p>Anda telah ditugaskan pada kegiatan baru.</p>

    <table cellpadding="6">
        <tr>
            <td><strong>Judul Kegiatan</strong></td>
            <td>: {{ $jadwal->judul }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>: {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>

        <tr>
            <td><strong>Waktu</strong></td>
            <td>:
                {{ $jadwal->waktu_mulai }}
                -
                {{ $jadwal->waktu_selesai }}
            </td>
        </tr>

        <tr>
            <td><strong>Ruangan</strong></td>
            <td>: {{ $jadwal->ruangan->nama ?? '-' }}</td>
        </tr>
    </table>

    <br>

    <p>
        Silakan login ke aplikasi <b>SIJAKAPRANA</b> untuk melihat informasi lebih lengkap.
    </p>

    <p>Terima kasih.</p>

</body>
</html>