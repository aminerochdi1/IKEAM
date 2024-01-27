<?php
require_once('C:/xampp/htdocs/tc-lib-pdf-8.0.55/src/tcpdf.php');

// Retrieve order details from POST data
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$email = $_POST['email'];
$address = $_POST['address'];
$orderId = $_POST['order-id'];
$orderItems = json_decode($_POST['order-items'], true);
$totalPrice = $_POST['total-price'];
$city = $_POST['city'];

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('IKEAM');
$pdf->SetAuthor('IKEAM');
$pdf->SetTitle('Invoice');

// Add a page
$pdf->AddPage();

// Set some content
$pdf->SetFont('helvetica', '', 12);

// Add order details to PDF content
$pdf->Cell(0, 10, 'Order ID: ' . $orderId, 0, true, 'L', 0);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Customer: ' . $firstname . ' ' . $lastname, 0, true, 'L', 0);
$pdf->Cell(0, 10, 'Email: ' . $email, 0, true, 'L', 0);
$pdf->Cell(0, 10, 'Shipping Address: ' . $address, 0, true, 'L', 0);
$pdf->Cell(0, 10, 'City: ' . $city, 0, true, 'L', 0);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Order Details:', 0, true, 'L', 0);
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(60, 10, 'Product', 1, 0, 'C');
$pdf->Cell(40, 10, 'Quantity', 1, 0, 'C');
$pdf->Cell(40, 10, 'Price', 1, 0, 'C');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 12);
foreach ($orderItems as $item) {
    $pdf->Cell(60, 10, $item['name'], 1, 0, 'C');
    $pdf->Cell(40, 10, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, $item['price'] . ' dhs', 1, 0, 'C');
    $pdf->Ln();
}
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(100, 10, 'Total Price:', 1, 0, 'C');
$pdf->Cell(60, 10, $totalPrice . ' dhs', 1, 0, 'C');

// Output PDF as a string
$invoice_content = $pdf->Output('invoice.pdf', 'S');

// Compose email message
$to = $email; // Send invoice to customer's email
$subject = "Invoice for Order ID: " . $orderId;
$message = "Dear " . $firstname . " " . $lastname . ",\n\nPlease find attached the invoice for your order.\n\nThank you for shopping with us!";
$headers = "From: your_email@example.com" . "\r\n" .
           "Reply-To: your_email@example.com" . "\r\n" .
           "X-Mailer: PHP/" . phpversion() . "\r\n" .
           "MIME-Version: 1.0\r\n" .
           "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n\r\n" .
           "--boundary\r\n" .
           "Content-Type: application/octet-stream; name=\"invoice.pdf\"\r\n" .
           "Content-Transfer-Encoding: base64\r\n" .
           "Content-Disposition: attachment\r\n\r\n" .
           $invoice_content . "\r\n" .
           "--boundary--";

// Send email with attachment
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email. Please try again later.";
}
?>
