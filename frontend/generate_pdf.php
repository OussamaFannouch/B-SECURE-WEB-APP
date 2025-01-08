<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('/Applications/XAMPP/xamppfiles/htdocs/Bseccopie/libraries/tcpdf/tcpdf.php');
require_once('../backend/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $meeting_id = $_POST['meeting_id'];
        $meeting_title = $_POST['meeting_title'];
        $meeting_date = $_POST['meeting_date'];
        $meeting_time = $_POST['meeting_time'];
        $user_email = $_POST['email'];

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document properties
        $pdf->SetCreator('B-Secure Club');
        $pdf->SetAuthor('B-Secure Club');
        $pdf->SetTitle('Meeting Invitation');
        
        // Remove header and footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(25, 15, 25);
        
        // Add a page
        $pdf->AddPage();

        // Add logo at the top left
        $pdf->Image('../frontend/media/logo2.png', 25, 15, 30);

        // HTML content
        $html = <<<EOD
        <style>
            body {
                font-family: 'Helvetica', sans-serif;
                color: #1a1a1a;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                padding: 20px;
            }
                        
            .title {
                color: #000;
                font-size: 24px;
                font-weight: bold;
                margin: 0;
                padding: 0;
                display: block;
                text-transform: uppercase;
            }
            .content {
                padding: 0 10px;
            }
            .info-section {
                margin: 20px 0;
                padding: 15px;
                background-color: #f8f9fa;
                border-left: 4px solid #00a6cc;
            }
            .info-label {
                color: #00a6cc;
                font-weight: bold;
                width: 120px;
                display: inline-block;
            }
            .info-box {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-left: 4px solid #4b45a9;
                padding: 20px;
                margin: 20px 0;
            }
            .info-title {
                color: #4b45a9;
                font-weight: bold;
                font-size: 14pt;
                margin-bottom: 15px;
            }
            .footer-line {
                border-top: 2px solid #00a6cc;
                margin: 30px 0 20px 0;
            }
            .signature {
                text-align: right;
                color: #4b45a9;
            }
            ul {
                list-style-type: none;
                padding-left: 0;
            }
            li {
                padding: 8px 0;
                padding-left: 20px;
                position: relative;
            }
            li:before {
                content: "•";
                color: #00a6cc;
                position: absolute;
                left: 0;
            }
        </style>

        <div class="header">
            <h1 class="title">MEETING INVITATION</h1>
        </div>

        <div class="content">
            <p>Dear participant,</p>

            <div class="info-section">
                <p><span class="info-label">Meeting Title:</span> $meeting_title</p>
                <p><span class="info-label">Date:</span> $meeting_date</p>
                <p><span class="info-label">Time:</span> $meeting_time</p>
                <p><span class="info-label">Location:</span> B3 Classroom</p>
                <p><span class="info-label">Email:</span> $user_email</p>
            </div>

            <div class="info-box">
                <div class="info-title">Important Information</div>
                <ul>
                    <li>Please arrive 5 minutes before the scheduled time</li>
                    <li>Bring your laptop and valid student ID</li>
                    <li>Meeting room will be accessible 15 minutes prior to start time</li>
                    <li>If you cannot attend, please notify us at least 24 hours in advance</li>
                </ul>
            </div>

            <div class="footer-line"></div>

            <div class="signature">
                Best regards,<br>
                <strong>B-Secure Club Team</strong>
            </div>
        </div>
        EOD;

        $pdf->writeHTML($html, true, false, true, false, '');

        // Database registration
        $stmt = $conn->prepare("INSERT INTO registrations (meeting_id, email) VALUES (?, ?)");
        $stmt->bind_param("is", $meeting_id, $user_email);
        $stmt->execute();

        // Output PDF
        $pdf->Output('Meeting_Invitation.pdf', 'D');
        exit;

    } catch (Exception $e) {
        error_log("PDF Generation Error: " . $e->getMessage());
        $_SESSION['error'] = "Unable to generate invitation. Please try again later.";
        header('Location: /frontend/dashboard.php');
        exit;
    }
}
?>  
