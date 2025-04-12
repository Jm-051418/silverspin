<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['orderId'];
} else {
    // echo "<script>alert('Error in finding the order')</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shipping Management</title>
  <link rel="stylesheet" type="text/css" href="shipping.css?v=1.0">
</head>
<body>

<div class="container">
<div class="title">Shipping Management</div>
  <div class="flex-box">
    <div class="product-box">
        <h3>Shipping Address</h3>
        <div class="form-row">
          <div>
            <label for="name">First Name</label>
            <input type="text" name="firstname" id="firstname" required>
          </div>
          <div>
            <label for="name">Last Name</label>
            <input type="text" name="lastname" id="lastname" required>
          </div>
        </div>
        <label for="contacts">Email Address</label>
        <input type="text" name="email" id="email" required>

        <label for="contacts">Phone Number</label>
        <input type="text" name="phone" id="phone" required>

        <label for="address">Address</label>
        <input type="text" name="address" id="address" required>

        <div class="form-row">
          <div>
            <label for="city">City</label>
            <input type="text" name="city" id="city" required>
          </div>
          <div>
            <label for="zip">Zip Code</label>
            <input type="text" name="zip" id="zip" required>
          </div>
        </div>

        <div class="button-row">
          <button type="button" class="btn btn-back" onclick="history.back()">Back</button>
          <button onclick="createShipping()" class="btn btn-confirm">Confirm</button>
        </div>
    </div>
  </div>
</div>

<script>
    function createShipping() {
      const firstName = document.getElementById('firstname').value.trim();
      const lastName = document.getElementById('lastname').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const address = document.getElementById('address').value.trim();
      const city = document.getElementById('city').value.trim();
      const zip = document.getElementById('zip').value.trim();

      if (!firstName || !lastName || !email || !phone || !address || !city || !zip) {
          alert('Please fill in all required fields.');
          return;
      }

      // Email format validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
          alert('Please enter a valid email address.');
          return;
      }

      // Phone number validation (digits, -, + only)
      const phoneRegex = /^[0-9\-+]+$/;
      if (!phoneRegex.test(phone)) {
          alert('Please enter a valid email address phone number.');
          return;
      }

      const orderId = <?php echo (int)$id ?>;
      const fullName = `${firstName} ${lastName}`;
      const fullAddress = `${address}, ${city} ${zip}`;

      fetch('http://localhost:8081/shippings', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({
              orderId: orderId,
              name: fullName,
              email: email,
              phone: phone,
              address: fullAddress
          }),
      })
      .then(response => response.text())
      .then(result => {
          fetch('http://localhost:8080/update', {
              method: 'PUT',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                  id: orderId,
                  trackingNumber: result
              }),
          });

          alert('Order is successfully shipped');
          window.location.href = 'index.php';
      })
      .catch(error => {
          alert('Error shipping the order: ' + error);
      });
  }
</script>


</body>
</html>