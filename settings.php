<?php

include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

$firstName = $lastName = $email = $password = $confirmPassword = $Err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['firstName'])) {
        $Err = 'PLEASE ENTER FIRST NAME';
    } else {
        $firstName = htmlspecialchars($_POST['firstName']);
        if (empty($_POST['lastName'])) {
            $Err = 'PLEASE ENTER LAST NAME';
        } else {
            $lastName = htmlspecialchars($_POST['lastName']);
            if (empty($_POST['email'])) {
                $Err = 'PLEASE ENTER EMAIL';
            } else {
                $email = htmlspecialchars($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $Err = 'PLEASE ENTER A VALID EMAIL';
                } else {
                    if (empty($_POST['password'])) {
                        $Err = 'PLEASE ENTER A PASSWORD';
                    } else {
                        $password = $_POST['password'];
                        if (strlen($password) < 8) {
                            $Err = 'PASSWORD MUST BE ATLEAST 8 CHARACTERS LONG';
                        } else {
                            $confirmPassword = $_POST['confirmPassword'];
                            if ($password !== $confirmPassword) {
                                $Err = 'PASSWORDS DO NOT MATCH';
                            } else {
                                $encrypted_password = hash('md5', $password);

                                $username = explode('@', $email)[0];

                                $check_email = "SELECT * FROM users WHERE email = '$email'";

                                $check_email_query = mysqli_query($conn, $check_email);

                                if (mysqli_num_rows($check_email_query) > 0) {
                                    $Err = "A USER WITH THIS EMAIL ALREADY EXISTS";
                                } else {
                                    $sql = "INSERT INTO users (username, first_name, last_name, password, email, is_admin) values ('$username', '$firstName', '$lastName', '$encrypted_password', '$email', 'false')";

                                    $sql_query = mysqli_query($conn, $sql);

                                    if ($sql_query) {
                                        $message = 'Account Created for ' . $username . ' Successfully!';
                                        header('Location: login.php?message=' . urldecode($message));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}




?>

<div class="container d-flex" style="margin-top: 100px;">
    <div style="flex: 1;"><?php include('./partials/sidebar.php') ?></div>
    <form action='' class='flex-2 border rounded p-3 pt-5 ms-3 form-style' method='POST' style="flex: 3;">
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class="mb-3">UPDATE PROFILE</h4>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">First Name</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your firstname" name='firstName' value="<?php echo $firstName ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your lastname" name='lastName' value="<?php echo $lastName ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Email</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your email" name='email' value="<?php echo $email ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your phone number" name='password' value="<?php echo $password ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Address</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your address" name='confirmPassword' value="<?php echo $confirmPassword ?>">
        </div>
        <div class="form-group mb-3">
            <label class="mb-2" for="image">Profile Picture (PNG, JPG, JPEG, WebP):</label> <br>
            <input type="file" class="form-control-file border rounded p-2" name="image" id="image" accept="image/png, image/jpeg, image/webp" style="width: 100% ">
        </div>
    </form>
</div>
<?php include('./partials/footer.php'); ?>