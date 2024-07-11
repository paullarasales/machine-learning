<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Product List</h1>
        <ul id="product-list" class="list-group">
            <!-- Products will be appended here -->
        </ul>

        <h2>Add Product</h2>
        <form id="add-product-form">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" id="description" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-product-form">
                        @csrf
                        <input type="hidden" id="edit-product-id">
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" class="form-control" id="edit-name" required>
                        </div>

                        <div class="form-group">
                            <label for="edit-description">Description</label>
                            <input type="text" class="form-control" id="edit-description" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-price">Price</label>
                            <input type="number" step="0.01" class="form-control" id="edit-price" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const productList = document.getElementById('product-list');
            const addProductForm = document.getElementById('add-product-form');
            const editProductForm = document.getElementById('edit-product-form');
            const editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
            
            async function fetchProducts() {
                productList.innerHTML = '';

                try {
                    const response = await fetch('/api/products');
                    const data = await response.json();
                    console.log(data); 

                    data.forEach(product => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        listItem.innerHTML = `${product.name} - $${product.price}
                            <div>
                                <button class="btn btn-warning btn-sm mr-2" onclick="editProduct(${product.id}, '${product.name}', '${product.description}', ${product.price})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Delete</button>
                            </div>
                        `;
                        productList.appendChild(listItem);
                    });
                } catch (error) {
                    console.error('Error Fetching Products:', error);
                }
            }

            fetchProducts();

            addProductForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const name = document.getElementById('name').value;
                const description = document.getElementById('description').value;
                const price = document.getElementById('price').value;

                try {
                    await fetch('/api/products', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ name, description, price }),
                    });

                    addProductForm.reset();
                    fetchProducts();
                } catch (error) {
                    console.error('Error adding new product:', error);
                }
            });

            window.editProduct = function(id, name, description, price) {
                document.getElementById('edit-product-id').value = id;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-description').value = description;
                document.getElementById('edit-price').value = price;
                editProductModal.show();
            }

            editProductForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const id = document.getElementById('edit-product-id').value;
                const name = document.getElementById('edit-name').value;
                const description = document.getElementById('edit-description').value;
                const price = document.getElementById('edit-price').value;

                try {
                    await fetch(`/api/products/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]');
                        },
                        body: JSON.stringify({ name, description, price });
                    });

                    editProductModal.hide();
                    fetchProducts();
                } catch(error) {
                    console.error('Error updating product:', error);
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
