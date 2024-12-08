<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>  <!-- Stripe.js -->
</head>
<body>

    <div id="payment-form">
        <h2>Pay for Appointment</h2>
        <div id="card-element"></div>  <!-- The Stripe Card Element -->

        <div id="card-errors" role="alert"></div> <!-- Display any error here -->

        <button id="generate-token">Pay</button> <!-- Button to generate token -->

        <h1 id="token-display"></h1>  <!-- This will display the generated token -->
    </div>

    <script>
        // Initialize Stripe and create an instance of Elements
        const stripe = Stripe('pk_test_51QSzAgGD49l9BuIFDH9CL99P2OIjFRf0x6a5z6SzzXsVLSStYh29N6KXbGH7HZpoaP3Tq74saUgu3ll9x0IW9zIv00g9gQYprW');  // Replace with your Stripe public key
        const elements = stripe.elements();

        // Create an instance of the card Element
        const card = elements.create('card');
        card.mount('#card-element');  // Mount the card element to the div

        // Get the appointment ID from the Blade template (passed from the backend)
        const appointmentId = {{ $appointment->id }};  // Make sure to replace with the actual appointment ID

        // Button click event to generate token
        document.getElementById('generate-token').addEventListener('click', async () => {
            const {error, token} = await stripe.createToken(card);

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                return;
            }

            // Display the token in the <h1> tag
            document.getElementById('token-display').textContent = `Generated Token: ${token.id}`;

            // Send the token and appointment_id to the backend for payment processing
            fetch(`/api/stripe/payment/${appointmentId}`, { // Use dynamic appointment ID
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    stripeToken: token.id,  // This is the token you should pass
                    appointment_id: appointmentId, // Pass the dynamic appointment ID
                }),
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(err => console.log(err));
        });
    </script>

</body>
</html>
