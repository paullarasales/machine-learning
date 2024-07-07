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

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const productList = document.getElementById('product-list');
            const addProductForm = document.getElementById('add-product-form');

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
                    console.error('Error Fetching Products', error);
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
                        body: JSON.stringify({name, description, price}),
                    });

                    addProductForm.reset();
                    fetchProducts();
                } catch (error) {
                    console.error('Error adding new product:', error);
                }
            });
        });
    </script>
</body>
</html>
