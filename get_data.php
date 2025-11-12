<?php 
header('Content-Type: application/json');
include 'config.php';

// Ambil semua data
$sql = "SELECT * FROM data_sensor";
$result = $conn->query($sql);

$data_all = [];
$suhu_max = null;
$suhu_min = null;
$suhu_total = 0;
$nilai_suhu_max_humid_max = [];
$month_year_max = [];

// Simpan semua data dulu
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_all[] = $row;

        // Hitung max, min, rata-rata suhu
        if ($suhu_max === null || $row['suhu'] > $suhu_max) $suhu_max = $row['suhu'];
        if ($suhu_min === null || $row['suhu'] < $suhu_min) $suhu_min = $row['suhu'];
        $suhu_total += $row['suhu'];

        // Ambil month-year dari timestamp
        $month_year = date('n-Y', strtotime($row['timestamp']));
        if (!in_array($month_year, $month_year_max)) $month_year_max[] = $month_year;
    }

    // Ambil semua data dengan suhu = suhu_max
    foreach($data_all as $row){
        if($row['suhu'] == $suhu_max){
            $nilai_suhu_max_humid_max[] = [
                'idx' => $row['id'],
                'suhun' => $row['suhu'],
                'humid' => $row['humidity'],
                'kecerahan' => $row['lux'],
                'timestamp' => $row['timestamp']
            ];
        }
    }
}

$suhu_rata = $suhu_total / count($data_all);

// Susun JSON
$output = [
    'suhumax' => $suhu_max,
    'suhumin' => $suhu_min,
    'suhurata' => round($suhu_rata, 2),
    'nilai_suhu_max_humid_max' => $nilai_suhu_max_humid_max,
    'month_year_max' => array_map(fn($my) => ['month_year'=>$my], $month_year_max)
];

echo json_encode($output, JSON_PRETTY_PRINT);
$conn->close();
?>