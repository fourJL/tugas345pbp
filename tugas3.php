<?php
// Membuat array
$data = array("apel", "jeruk", "mangga", "pisang", "anggur");

// 1. Cek apakah variabel adalah array
if (is_array($data)) {
    echo "Variabel adalah array \n";
} else {
    echo "Variabel bukan array \n";
}

// 2. Menghitung jumlah elemen dalam array
$jumlah = count($data);
echo "Jumlah elemen dalam array: " . $jumlah . "\n";

// 3. Mengurutkan array (ascending)
sort($data);
echo "Array setelah diurutkan (sort): \n";
foreach ($data as $item) {
    echo $item . "\n";
}

// 4. Mengacak urutan array
shuffle($data);
echo "\nArray setelah diacak (shuffle): \n";
foreach ($data as $item) {
    echo $item . "\n";
}
?>