<?php
ini_set('display_errors',1);
function distance($a, $b)
{
    $keys = array_keys($a);
    $c = 0;
    foreach($keys as $key)
    {
        $c += pow(($a[$key] - $b[$key]),2);
    }
    return sqrt($c);
}
$data = [
    [1,9],
    [2,13],
    [3,8],
    [3,12],
    [5,3],
    [7,6],
    [8,3],
    [9,6],
];

// tentukan berapa jml cluster k = 2
$k = 2;

// inisiasi cluster
for($i=0; $i<$k; $i++)
{
    $cluster[$i] = [];
}

// pilih centroid scr random
$centroid_keys = array_rand($data,$k);
foreach($centroid_keys as $c)
{
    $centroid[] = $data[$c];
}

$loop = true;
$max_iteration = 100;
$current_iteration = 1;
while($loop)
{
    echo "<br />Iterasi ke-".$current_iteration."<br />";
    echo "-------------------------<br />";
    // tentukan jarak centroid ke masing-masing titik
    $jarak = [];
    foreach($data as $titik => $d)
    {
        foreach($centroid as $c => $koord)
        {
            $jarak[$titik][$c] = distance($d,$koord);
        }
    }

    // masukkan titik ke dalam cluster terdekat
    foreach($data as $titik => $d)
    {
        $min_c = array_keys($jarak[$titik],min($jarak[$titik]));

        $point = $titik+1;
        
        echo "Titik ".$point." masuk ke cluster ".$min_c[0]."<br />";

        $cluster[$current_iteration][$min_c[0]][] = $titik;
    }

    // cek apakah anggota cluster berubah?
    if($current_iteration > 1)
    {
        $is_diff = false;
        foreach($centroid as $c => $koord)
        {
            $diff = array_diff($cluster[$current_iteration][$c],$cluster[$current_iteration-1][$c]);

            if(count($diff) > 0)
            {
                // ada yang beda
                $is_diff = true;
                break;
            }
        }

        if($is_diff == false)
        {
            break;
        }
    }

    // hitung centroid baru
    foreach($cluster[$current_iteration] as $c => $points)
    {
        unset($jumlah);
        foreach($points as $p)
        {
            $koord = $data[$p];

            $feature = array_keys($koord);

            // jumlahkan x dan y (koordinat)
            foreach($feature as $f)
            {
                $jumlah[$f][] = $koord[$f];
            }
        }
        foreach($jumlah as $f => $arr)
        {
            $centroid[$c][$f] = array_sum($arr)/count($arr);
        }
    }

    $current_iteration++;

    if($current_iteration > $max_iteration)
    {
        $loop = false;
    }
}