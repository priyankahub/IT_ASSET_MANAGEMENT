<?php
include("../config/db.php");

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=maintenance_report.csv");

$output = fopen("php://output", "w");

fputcsv($output, [
    'Equipment','Maintenance Type','Start Date','Status','Remarks'
]);

$res=mysqli_query($conn,"
SELECT e.type,e.make,m.maintenance_type,m.start_date,m.status,m.remarks
FROM maintenance m
JOIN equipment e ON m.equipment_id=e.id
");

while($r=mysqli_fetch_assoc($res)){
    fputcsv($output,[
        $r['type']." ".$r['make'],
        $r['maintenance_type'],
        $r['start_date'],
        $r['status'],
        $r['remarks']
    ]);
}

fclose($output);
