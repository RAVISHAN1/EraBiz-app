@extends('ravish.app')

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <div class="container">
                @include('ravish.searchField')

                <table class="table mt-3" id="data-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be populated dynamically using Ajax -->
                    </tbody>
                </table>

            </div>

        </div>
    </main>
    @include('create')
    @include('edit')
    @include('image')
</div>

<script>
    // Function to retrieve all products and populate the table
    function getAllProducts() {
        var name = $('#filterName').val();
        var order = $('#filterOrder').val();

        $.ajax({
            url: '/api/products',
            type: 'GET',
            data: {
                name: name,
                order: order,
            },
            headers: {
                'Authorization': 'Bearer ' + getAccessToken()
            },
            success: function(response) {
                // Clear the existing table rows
                $('#data-table tbody').empty();

                var products = response.data; // Assuming the API response contains a "data" property with an array of objects

                var tableBody = $('#data-table tbody');
                $.each(products, function(index, item) {
                    var row = $('<tr>');
                    row.append($('<td>').text(item.id));
                    row.append($('<td>').text(item.name));
                    row.append($('<td>').text(item.description));
                    row.append($('<td>').text(item.price));

                    // Add the product image
                    var img = $('<img src="' + item.image_url + '" alt="' + item.name + '" style="width: 60px; height: 60px">');
                    var button_img = $('<button type="button" class="btn btn-sm btn-secondary mx-2">Danger</button>').text('upload');
                    button_img.click(function() {
                        editImage(item.id);
                    });
                    row.append($('<td>').append(img, button_img));

                    // delete button element append to the row
                    var button_1 = $('<button type="button" class="btn btn-warning mx-2">Danger</button>').text('Edit');
                    button_1.click(function() {
                        editProduct(item.id);
                    });

                    var button_2 = $('<button type="button" class="btn btn-danger">Danger</button>').text('Delete');
                    button_2.click(function() {
                        deleteProduct(item.id);
                    });
                    row.append($('<td>').append(button_1, button_2));

                    tableBody.append(row);
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    // Function to handle product deletion
    function deleteProduct(productId) {
        var confirmed = confirm('Are you sure you want to delete this product?');
        if (confirmed) {
            $.ajax({
                url: '/api/products/' + productId,
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + getAccessToken()
                },
                success: function(response) {
                    // Refresh the table after successful deletion
                    getAllProducts();
                }
            });
        }
    }

    // Function to handle product creating
    function createProduct() {
        $('#createModal').modal('show');
    }

    // Function to handle product editing
    function editProduct(productId) {
        // Retrieve product details
        $.ajax({
            url: '/api/products/' + productId,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getAccessToken()
            },
            success: function(response) {
                // Populate the form fields with the product details
                $('#editModal #editProductName').val(response.name);
                $('#editModal #editProductDes').val(response.description);
                $('#editModal #editProductPrice').val(response.price);
                $('#editModal #editProductId').val(response.id);
                // Show the edit modal
                $('#editModal').modal('show');
            }
        });
    }

    // Function to handle product store
    $('#addProductForm').submit(function(event) {
        event.preventDefault();

        // var formData = {
        //     name: $('#productName').val(),
        //     description: $('#productDes').val(),
        //     price: $('#productPrice').val(),
        //     image_url: null,
        // };
        var formData = new FormData($(this)[0]);

        // Send the data via Ajax
        $.ajax({
            url: '/api/products',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'Authorization': 'Bearer ' + getAccessToken()
            },
            success: function(response) {
                // Clear the form fields
                $('#productName').val('');
                $('#productDes').val('');
                $('#productPrice').val('');
                $('#image-input').val('');

                $('#createModal').modal('hide');

                // Refresh the table after successful product addition
                getAllProducts();
            }
        });
    });

    // Function to handle product update
    $('#editProductForm').submit(function(e) {
        e.preventDefault();

        // Get form data
        var productId = $('#editProductId').val();
        var formData = {
            name: $('#editProductName').val(),
            description: $('#editProductDes').val(),
            price: $('#editProductPrice').val()
        };

        // Send Ajax request to update the product
        $.ajax({
            url: '/api/products/' + productId,
            type: 'PUT',
            data: formData,
            headers: {
                'Authorization': 'Bearer ' + getAccessToken()
            },
            success: function(response) {
                // Hide the edit modal
                $('#editModal').modal('hide');

                // Refresh the table after successful update
                getAllProducts();
            }
        });
    });

    // Function to change image
    function editImage(productId) {
        $('#imageModal #id').val(productId);
        $('#imageModal').modal('show');
    }

    $('#uploadForm').submit(function(event) {
        event.preventDefault();

        var productId = $('#id').val();
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: '/api/products/image/' + productId,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'Authorization': 'Bearer ' + getAccessToken()
            },
            success: function(response) {
                $('#imageModal').modal('hide');
                getAllProducts();
                //alert(response.message);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }
        });
    });

    // Document ready function
    $(document).ready(function() {
        // Load products when the page is ready
        getAllProducts();

        // Store product button click event
        // $(document).on('click', '.store-btn', function() {
        //     alert('d');
        //     var productId = $(this).data('id');
        //     storeProduct(productId);
        // });
    });

    $('#filterForm').submit(function(e) {
        e.preventDefault();

        getAllProducts();
    });
</script>
@stop