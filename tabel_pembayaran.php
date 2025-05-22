<?php
if ($result->num_rows > 0):
    $jumlahTagihanPerBulan = 125000;
    $dataPembayaran = [];

    // Ambil dan kelompokkan data pembayaran per bulan
    while ($row = $result->fetch_assoc()) {
        $bulan = $row['bulan_tagihan'];
        if (!isset($dataPembayaran[$bulan])) {
            $dataPembayaran[$bulan] = [
                'total_bayar' => 0,
                'detail' => [],
                'tanggal' => $row['tanggal_pembayaran'],
                'petugas' => $row['petugas']
            ];
        }
        $dataPembayaran[$bulan]['total_bayar'] += $row['nominal'];
        $dataPembayaran[$bulan]['detail'][] = $row;
    }

    // Urutkan bulan tagihan dari yang paling awal
    ksort($dataPembayaran);

    // Hitung status kumulatif per bulan
    $total_bayar_kumulatif = 0;
    $bulan_ke = 0;
?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Bulan Tagihan</th>
        <th>Total Bayar</th>
        <th>Status</th>
        <th>Selisih</th>
        <th>Tanggal Bayar Terakhir</th>
        <th>Petugas</th>
    </tr>

    <?php foreach ($dataPembayaran as $bulan => $info): 
        $bulan_ke++;
        $total_tagihan_kumulatif = $bulan_ke * $jumlahTagihanPerBulan;
        $total_bayar_kumulatif += $info['total_bayar'];
        $selisih = $total_bayar_kumulatif - $total_tagihan_kumulatif;
        $status = ($total_bayar_kumulatif >= $total_tagihan_kumulatif) ? 'Lunas' : 'Belum Lunas';
    ?>
    <tr>
        <td><?= date('F Y', strtotime($bulan)) ?></td>
        <td>Rp<?= number_format($info['total_bayar'], 0, ',', '.') ?></td>
        <td><?= $status ?></td>
        <td>
            <?php
            if ($selisih > 0) {
                echo 'Lebih Rp' . number_format($selisih, 0, ',', '.');
            } elseif ($selisih < 0) {
                echo 'Kurang Rp' . number_format(abs($selisih), 0, ',', '.');
            } else {
                echo 'Pas';
            }
            ?>
        </td>
        <td><?= htmlspecialchars($info['tanggal']) ?></td>
        <td><?= htmlspecialchars($info['petugas']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php else: ?>
    <p><em>Belum ada data pembayaran.</em></p>
<?php endif; ?>
