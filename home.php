<?php
require("configpage.php");
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pagetitle; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base Styles */
        body {
            font-family: 'Fira Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			margin-top:20px;
        }

        .hero-image {
            width: 100%;
            height: auto;
            display: block;
            border: 1px solid black;
			border-radius: 3px;
        }

        .product-details {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
			border: 1px solid black;
			border-radius: 3px;
			overflow:auto;
        }

        .left-column {
            flex: 0 0 60%;
            padding: 10px;
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        .right-column {
            flex: 0 0 40%;
            padding: 10px;
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        .product-title {
            font-size: 36px;
            padding: 10px;
			border: 1px solid black;
			border-bottom:0px;
            margin: 0px;
            font-weight: 500;
        }

        .product-description {
			border: 1px solid black;
			padding:10px;
			margin:0px;
            font-size: 18px;
            font-weight: 400;
        }

        .buy-button {
            background-color: #ff6347;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .cart-info {
			font-size:16px;
			font-weight:400;
            padding: 15px;
			border: 1px solid black;
			margin-bottom:20px;
        }
		
		
        .buycode-container {
			text-align:center; 
			overflow:hidden;
			padding: 15px;
            border: 1px solid #000;
            overflow: hidden;
        }

        .buycode-container img {
			margin:0 auto;
            max-width: 100%;
            height: auto;
            display: block;
        }
		
        .footer-container {
            width: 100%;
            height: auto;
            display: block;
			margin-left:10px;
			margin-right:10px;
			margin-bottom:10px;
			padding:10px;
        }		
		
        /* Responsive Styles */
        @media (max-width: 768px) {
            .product-details {
                flex-direction: column;
            }

            .left-column, .right-column {
                max-width: 100%;
                flex-basis: 100%;
            }
        }

    </style>
</head>
<body>

    <div class="container">
        <img src="img/hero.png" alt="Hero Image" class="hero-image">
        <div class="product-details">
            <div class="left-column">
                <h1 class="product-title"><?php echo $productname; ?></h1>
                <div class="product-description">
<?php echo (base64_decode($productdetails)); ?>
                </div>
            </div>

            <div class="right-column">
                <div class="cart-info">
<?php echo (base64_decode($cartinfo)); ?>
				</div>

				<div class="buycode-container">
<?php echo (base64_decode($buttoncode)); ?>
				</div>
            </div>
			<div class="footer-container">
<?php echo (base64_decode($footerhtml)); ?>
			</div>
        </div>
    </div>
</body>
</html>
