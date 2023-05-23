<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EraBiz-task</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/main.css"> -->
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div>
        <main>
            <div class="container-fluid px-4">
                <div class="container mt-5">
                    <h2 class="text-center mt-3">SignUp</h2>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <form id="registerForm">
                                <div class="form-group">
                                    <label for="loginName">Name</label>
                                    <input type="text" class="form-control" id="loginName" placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label for="loginEmail">Email address</label>
                                    <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="loginPassword">Password</label>
                                    <input type="password" class="form-control" id="loginPassword" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label for="loginPasswordConfirm">Confirm Password</label>
                                    <input type="password" class="form-control" id="loginPasswordConfirm" placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
<script>
    // Function to handle product store
    $('#registerForm').submit(function(event) {
        event.preventDefault();

        var formData = {
            name: $('#loginName').val(),
            email: $('#loginEmail').val(),
            password: $('#loginPassword').val(),
            password_confirmation: $('#loginPasswordConfirm').val(),
        };

        // Send the data via Ajax
        $.ajax({
            url: '/api/register',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Clear the form fields
                $('#loginEmail').val('');
                $('#loginPassword').val('');
                $('#loginPasswordConfirm').val('');

                sessionStorage.setItem('access_token', response.access_token)

                window.location.href = '/home';
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    });

    $(document).ready(function() {
        if (sessionStorage.getItem('access_token')) {
            window.location.href = '/home';
        }
    });
</script>

</html>