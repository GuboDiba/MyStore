<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light" style="position: fixed; top: 0; left: 0; width: 100%; background-color: #ff6f61; color: white; padding: 15px 0; z-index: 1000;">
    <div class="container">
        <a class="navbar-brand" href="#" style="font-size: 1.5rem; font-weight: bold; color: white;">MyStore</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/" style="font-size: 1rem; color: white; transition: color 0.3s ease;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-size: 1rem; color: white; transition: color 0.3s ease;">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/checkout" style="font-size: 1rem; color: white; transition: color 0.3s ease;">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login" style="font-size: 1rem; color: white; transition: color 0.3s ease;">Login</a>
                </li>
                <li class="nav-item" style="margin-left: 100px; margin-right: -100px;  margin-top: 10px;">
                <a href="/cart" style="text-decoration: none;">
    <div style="display: flex; align-items: center; cursor: pointer; gap: 5px; position: relative;">
    <i class="fas fa-shopping-cart" style="font-size:20px; width: 30px; height: 30px; "></i>

        <!-- Badge for the cart count -->
        <span id="cart-count" style="
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        ">0</span>
    </div>
</a>


                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Optional: Add some padding at the top to prevent content from being hidden behind the navbar -->
<div style="padding-top: 80px;"></div>


<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
