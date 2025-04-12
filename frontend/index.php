<?php
$url = 'http://localhost:8080/orders';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true);

$response = curl_exec($ch);

if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    $data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">   
<div class="top-buttons">
    <div class="title">Order Management</div>
    <button class="create-btn" onclick="openModal()">New Order</button>
</div>
<div class="order-container">
    <?php foreach ($data as $item): ?>
        <div class="order-card">
            <div class="order-buttons">
                <div class="edit-delete-buttons">
                    <?php if ($item['orderStatus'] !== 'Shipped' && $item['orderStatus'] !== 'Cancelled'): ?>
                        <!-- <i onclick="editOrder(<?= $item['id'] ?>)" class="edit-btn fas fa-edit"></i> -->
                    <?php endif; ?>
                    <i onclick="deleteOrder(<?= $item['id'] ?>)" class="delete-btn fas fa-trash"></i>
                </div>
            </div><br><br>
            <div class="order-details">
                <div class="orderNumber"><strong>Order Number <?= htmlspecialchars($item['id']) ?></strong></div>
                <div class="orderStatus">
                    Status: <span class="status-value <?= strtolower($item['orderStatus']) ?>">
                        <?= htmlspecialchars($item['orderStatus']) ?>
                    </span>
                </div>
                <div class="orderTracking">
                <?php if ($item['orderStatus'] === 'Shipped'): ?>
                    Tracking Number: <?= htmlspecialchars($item['trackingNumber']) ?><br>
                <?php endif; ?></div>
                <div class="productDetails">
                Item: <?= htmlspecialchars($item['productName']) ?><br>
                Quantity: <?= htmlspecialchars($item['productQuantity']) ?><br>
                Price: $<?= htmlspecialchars($item['productPrice']) ?><br>
                </div>
            </div>
            <form class="btn-form" action="shipping.php" method="POST">
                <input type="hidden" name="orderId" value="<?= $item['id'] ?>">

                <?php if ($item['orderStatus'] === 'Pending') : ?>
                    <button class="checkout-btn" type="submit" name="action" value="checkout">Checkout</button>
                <?php else : ?>
                    <button
                        class="cancel-btn"
                        type="button"
                        onclick="cancelOrder(<?= $item['id'] ?>)"
                        <?= $item['orderStatus'] === 'Cancelled' ? 'disabled' : '' ?>
                    >
                        Cancel
                    </button>
                <?php endif; ?>
            </form>

        </div>
    <?php endforeach; ?>
</div>

<?php } curl_close($ch); ?>

<!-- Modal -->
<div class="modal" id="productModal">
<div class="modal-content">
    <button class="close-btn" onclick="closeModal()">×</button>
    <button class="nav-arrow left" onclick="changeProduct(-1)">&#9664;</button>
    <div class="slider-container" id="sliderContainer"></div>
    <button class="nav-arrow right" onclick="changeProduct(1)">&#9654;</button>
  </div>
</div>
</div>




<script>
const orderData = <?php echo json_encode($data ?? []); ?>;
const products = [
    { name: "Laptop", description: "Powerful performance for work and play", price: 800, icon: "fas fa-laptop"},
    { name: "Smartphone", description: "Stay connected on the go", price: 200, icon: "fas fa-mobile-alt"},
    { name: "Tablet", description: "Portable entertainment and productivity", price: 520, icon: "fas fa-tablet-alt" },
    { name: "Computer", description: "Desktop performance powerhouse", price: 850, icon: "fas fa-desktop" }
];

let currentIndex = 0;

// function createOrder() {
//     const name = document.getElementById('productName').textContent;
//     const quantity = parseInt(document.getElementById('productQuantity').value);
//     const rawValue = document.getElementById('productPrice').textContent;
//     const unitPrice = parseInt(rawValue.replace('$', '')); 
//     const price = (unitPrice * quantity);

//     fetch('http://localhost:8080/orders', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify({
//             productName: name,
//             productQuantity: quantity,
//             productPrice: price,
//             orderStatus: "Pending",
//             trackingNumber: ""
//         }),
//     })
//     .then(response => {
//         if (response.ok) {
//             alert('Order created successfully!');
//             location.reload();
//         } else {
//             alert('Failed to create order');
//         }
//     })
//     .catch(error => alert('Error creating order: ' + error));
// }

function updateOrder() {
    const orderId = document.getElementById('orderId').value;
    const editedProductName = document.getElementById('productName').value;
    const editedProductQuantity = parseInt(document.getElementById('productQuantity').value);
    const editedOrderStatus = document.getElementById('orderStatus').value;
    const editedTrackingNumber = document.getElementById('trackingNumber').value;

    const unitPrice = parseFloat(orderData.find(o => o.id == orderId).productPrice) /
                      parseFloat(orderData.find(o => o.id == orderId).productQuantity);
    const calculatedPrice = (unitPrice * editedProductQuantity);

    fetch('http://localhost:8080/orders', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: orderId,
            productName: editedProductName,
            productQuantity: editedProductQuantity,
            productPrice: calculatedPrice,
            orderStatus: editedOrderStatus,
            trackingNumber: editedTrackingNumber
        }),
    })
    .then(response => {
        if (response.ok) {
            alert('Order updated successfully!');
            location.reload();
        } else {
            alert('Failed to update order');
        }
    })
    .catch(error => alert('Error updating order: ' + error));
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        fetch('http://localhost:8080/orders/' + orderId, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
        })
        .then(response => {
            if (response.ok) {
                alert('Order deleted successfully!');
                location.reload();
            } else {
                alert('Failed to delete the order');
            }
        })
        .catch(error => alert('Error deleting order: ' + error));
    }
}

function cancelOrder(orderId) {
    const order = orderData.find(order => order.id === orderId);
    fetch('http://localhost:8080/orders', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: orderId,
            productName: order.productName,
            productQuantity: order.productQuantity,
            productPrice: order.productPrice,
            orderStatus: "Cancelled",
            trackingNumber: order.trackingNumber
        }),
    })
    .then(response => {
        if (response.ok) {
            alert('Order is cancelled successfully!');
            location.reload();
        } else {
            alert('Failed to cancel order');
        }
    })
    .catch(error => alert('Error cancelling order: ' + error));
}
window.onload = function () {
    const slider = document.getElementById('sliderContainer');

    function renderProducts() {
        slider.innerHTML = '';
        products.forEach((product, index) => {
            const box = document.createElement('div');
            box.className = 'product-box';
            box.setAttribute('data-index', index);
            box.innerHTML = `
                <div class="product-header">
                    <span class="productName">${product.name}</span>
                    <span class="productPrice">$${product.price}</span>
                </div>
                <div class="product-description">${product.description}</div>
                <div class="product-image">
                    <i class="${product.icon}"></i>
                </div>
                <div class="quantity-selector">
                    <button onclick="minusQuantity(this)">−</button>
                    <input type="text" class="productQuantity" value="1" min="1">
                    <button onclick="plusQuantity(this)">+</button>
                </div>
                <button type="submit" class="order-button" onclick="createOrder()">Order</button>
            `;
            slider.appendChild(box);
        });
    }

    window.plusQuantity = function (btn) {
        const input = btn.parentElement.querySelector('.productQuantity');
        let currentValue = parseInt(input.value) || 1;
        input.value = currentValue + 1;
    }

    window.minusQuantity = function (btn) {
        const input = btn.parentElement.querySelector('.productQuantity');
        let currentValue = parseInt(input.value) || 1;
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    window.createOrder = function () {
        const currentBox = document.querySelector(`.product-box[data-index="${currentIndex}"]`);
        const name = currentBox.querySelector('.productName').textContent;
        const rawPrice = currentBox.querySelector('.productPrice').textContent;
        const pricePerUnit = parseInt(rawPrice.replace('$', ''));
        const quantity = parseInt(currentBox.querySelector('.productQuantity').value) || 1;
        const totalPrice = pricePerUnit * quantity;

        fetch('http://localhost:8080/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                productName: name,
                productQuantity: quantity,
                productPrice: totalPrice,
                orderStatus: "Pending",
                trackingNumber: ""
            }),
        })
        .then(response => {
            if (response.ok) {
                alert('Order created successfully!');
                location.reload();
            } else {
                alert('Failed to create order');
            }
        })
        .catch(error => alert('Error creating order: ' + error));
    }

    window.changeProduct = function (direction) {
        const totalProducts = products.length;
        currentIndex = (currentIndex + direction + totalProducts) % totalProducts;
        const sliderWidth = 100;
        slider.style.transform = `translateX(-${currentIndex * sliderWidth}%)`;
    }

    window.openModal = function () {
        document.getElementById('productModal').classList.add('show');
    }

    window.closeModal = function () {
        document.getElementById('productModal').classList.remove('show');
    }

    renderProducts();
    changeProduct(0); // initial
};
</script>

</body>
</html>
