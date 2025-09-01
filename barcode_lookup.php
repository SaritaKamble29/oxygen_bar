<?php
if (isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    // Replace with your real API endpoint & key
    $apiKey = "d3fa9d6a6cmshce9af65dab87884p135504jsn8150c2099955";
    $url = "https://api.upcitemdb.com/prod/trial/lookup?upc=" . $barcode;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "user_key: $apiKey"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['items'][0])) {
        $product = $data['items'][0];

        // Show details
        echo "<h3>Product Found:</h3>";
        echo "Name: " . $product['title'] . "<br>";
        echo "Brand: " . $product['brand'] . "<br>";
        echo "Category: " . $product['category'] . "<br>";
        echo "Image: <img src='" . $product['images'][0] . "' width='100'><br>";

        // Save to your products table (optional)
        include("config.php");
        $stmt = $conn->prepare("INSERT INTO products (name, barcode) VALUES (?, ?)");
        $stmt->bind_param("ss", $product['title'], $barcode);
        $stmt->execute();
        echo "<p>Saved into database!</p>";
    } else {
        echo "❌ No details found for this barcode!";
    }
}
?>

<form method="post">
    <input type="text" name="barcode" placeholder="Enter barcode manually">
    <button type="submit">Search</button>
</form>
