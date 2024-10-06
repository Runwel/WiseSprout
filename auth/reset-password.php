<?php

include '../includes/dbcon.php';

$errors = [];
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if (empty($password) || empty($confirm_password)) {
        $errors[] = "Both password fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check if token is valid
        $sql = "SELECT id, email FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password
            $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user['id']);
            $stmt->execute();

            $success_message = 'Your password has been reset successfully.';

            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Invalid or expired token.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <style>
        body{
            padding-top: 7rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='560' preserveAspectRatio='none' viewBox='0 0 1440 560'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1029%26quot%3b)' fill='none'%3e%3crect width='1440' height='560' x='0' y='0' fill='%230e2a47'%3e%3c/rect%3e%3cpath d='M417.12 53.45 a175.97 175.97 0 1 0 351.94 0 a175.97 175.97 0 1 0 -351.94 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M1292.532%2c628.399C1336.897%2c626.802%2c1378.617%2c606.271%2c1401.13%2c568.009C1423.964%2c529.201%2c1424.442%2c481.144%2c1402.219%2c441.983C1379.707%2c402.313%2c1338.14%2c375.94%2c1292.532%2c376.643C1248.025%2c377.329%2c1211.9%2c407.38%2c1188.551%2c445.277C1163.729%2c485.564%2c1146.123%2c535.2%2c1169.488%2c576.349C1193.056%2c617.855%2c1244.833%2c630.116%2c1292.532%2c628.399' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M396.247%2c644.997C429.922%2c643.863%2c450.472%2c612.125%2c467.219%2c582.888C483.825%2c553.897%2c499.423%2c520.854%2c484.47%2c490.977C468.264%2c458.595%2c432.449%2c440.253%2c396.247%2c441.078C361.367%2c441.873%2c331.375%2c464.185%2c314.725%2c494.845C298.846%2c524.087%2c300.604%2c558.723%2c316.481%2c587.966C333.196%2c618.753%2c361.235%2c646.176%2c396.247%2c644.997' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M27.181%2c211.245C41.33%2c210.418%2c53.596%2c202.058%2c60.467%2c189.662C67.12%2c177.657%2c67.116%2c163.234%2c60.435%2c151.245C53.566%2c138.919%2c41.265%2c130.8%2c27.181%2c129.935C11.334%2c128.962%2c-5.459%2c133.284%2c-13.986%2c146.677C-23.066%2c160.937%2c-22.694%2c179.739%2c-13.606%2c193.993C-5.1%2c207.334%2c11.386%2c212.168%2c27.181%2c211.245' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M838.74%2c174.395C859.186%2c173.875%2c881.249%2c171.106%2c892.159%2c153.806C903.658%2c135.572%2c900.299%2c112.312%2c889.746%2c93.515C878.941%2c74.269%2c860.81%2c58.921%2c838.74%2c58.654C816.266%2c58.382%2c797.051%2c72.944%2c785.553%2c92.256C773.746%2c112.086%2c767.853%2c137.276%2c780.397%2c156.648C792.239%2c174.936%2c816.96%2c174.949%2c838.74%2c174.395' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M238.038%2c208.666C262.068%2c207.072%2c284.382%2c196.276%2c296.808%2c175.646C309.655%2c154.317%2c312.565%2c127.436%2c299.866%2c106.019C287.384%2c84.969%2c262.497%2c75.738%2c238.038%2c76.536C215.013%2c77.287%2c195.854%2c90.84%2c182.823%2c109.837C167.272%2c132.508%2c150.604%2c160.454%2c164.013%2c184.454C177.584%2c208.745%2c210.274%2c210.508%2c238.038%2c208.666' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M776.24 71.74 a146.15 146.15 0 1 0 292.3 0 a146.15 146.15 0 1 0 -292.3 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M515.146%2c429.486C539.864%2c431.705%2c567.898%2c425.727%2c579.73%2c403.912C591.248%2c382.677%2c577.554%2c358.472%2c564.788%2c337.963C553.007%2c319.036%2c537.439%2c299.98%2c515.146%2c300.152C493.056%2c300.323%2c476.751%2c318.99%2c466.815%2c338.72C457.95%2c356.323%2c458.683%2c376.479%2c467.946%2c393.875C477.874%2c412.52%2c494.107%2c427.597%2c515.146%2c429.486' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M-68.6 496.22 a178.24 178.24 0 1 0 356.48 0 a178.24 178.24 0 1 0 -356.48 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M151.72 224.21 a114.6 114.6 0 1 0 229.2 0 a114.6 114.6 0 1 0 -229.2 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M819.072%2c431.628C866.253%2c429.036%2c893.403%2c383.075%2c915.877%2c341.51C936.858%2c302.707%2c954.137%2c258.522%2c933.847%2c219.354C912.194%2c177.555%2c866.146%2c154.6%2c819.072%2c154.638C772.065%2c154.676%2c728.501%2c179.052%2c704.619%2c219.54C680.342%2c260.697%2c679.217%2c311.211%2c701.91%2c353.263C725.826%2c397.58%2c768.79%2c434.39%2c819.072%2c431.628' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1029'%3e%3crect width='1440' height='560' fill='white'%3e%3c/rect%3e%3c/mask%3e%3c/defs%3e%3c/svg%3e");
            background-size: cover;
            background-position: center;
        }
        .container-wrapper {
            height: 100vh;
        }
        .starter-template img {
            height: 200px;
            width: 200px;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>
    <main role="main" class="container" style="height: 100vh;">
        <div>
            <div class="starter-template text-center">
                <img src="../assets/pictures/WiseSprout.png" alt="WiseSprout">
                <h3>Reset Password</h3>
                <?php 
                // Display errors
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo '<p class="error-message">' . $error . '</p>';
                    }
                } 
                // Display success message
                if ($success_message) {
                    echo '<p class="success-message">' . $success_message . '</p>';
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="New Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
