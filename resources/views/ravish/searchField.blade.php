<form id="filterForm">
    <div class="row">
        <div class="col-md-4">
            <button type="button" onclick="createProduct()" class="btn btn-success"><em class="fas fa-plus-circle"></em> Add New</button>
        </div>

        <div class="col-md-4">
            <select class="form-control" id="filterOrder" onchange="getAllProducts()">
                <option value="1">Price : Low to High</option>
                <option value="2">Price : High to Low</option>
            </select>
        </div>

        <div class="col-md-4 input-fields--medium">
            <div class="input-group">
                <input type="text" class="form-control" id="filterName" name="name" placeholder="Search" value="{{ request('name') }}" autocomplete="off">
                <span class="input-group-append">
                    <button class="btn btn-success" type="submit">Search</button>
                </span>
            </div>
        </div>
    </div>
</form>