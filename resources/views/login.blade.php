@extends('ravish.app')

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="container">

                <form id="loginForm">
                    <div class="form-group">
                        <label for="loginEmail">Email address</label>
                        <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" class="form-control" id="loginPassword" placeholder="Password">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>
    </main>
    @include('create')
    @include('edit')
</div>

<script>
    // Function to handle product store
    $('#loginForm').submit(function(event) {
        event.preventDefault();

        var formData = {
            email: $('#loginEmail').val(),
            password: $('#loginPassword').val(),
        };

        // Send the data via Ajax
        $.ajax({
            url: '/api/login',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Clear the form fields
                $('#loginEmail').val('');
                $('#loginPassword').val('');

                sessionStorage.setItem('access_token', response.access_token)

                window.location.href = '/home';
            },
            error: function(response) {
                alert('Invalid credentials');
            }
        });
    });
</script>
@stop