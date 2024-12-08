<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>  <!-- Stripe.js -->
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS -->
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div id="payment-form" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold text-center text-gray-800 mb-6">Pay for Appointment</h2>

        <!-- Stripe Card Element -->
        <div id="card-element" class="border border-gray-300 rounded-md p-3 mb-4"></div>

        <!-- Display error messages -->
        <div id="card-errors" class="text-red-500 text-sm mb-4" role="alert"></div>

        <!-- Payment Button -->
        <button id="generate-token" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
            Pay
        </button>

        <!-- Display token -->
        <h1 id="token-display" class="text-green-500 font-semibold text-center text-xl mt-4 break-words"></h1>
    </div>

    <script>
        // Initialize Stripe and create an instance of Elements
        const stripe = Stripe('pk_test_51QSzAgGD49l9BuIFDH9CL99P2OIjFRf0x6a5z6SzzXsVLSStYh29N6KXbGH7HZpoaP3Tq74saUgu3ll9x0IW9zIv00g9gQYprW');  
        const elements = stripe.elements();

        // Create an instance of the card Element
        const card = elements.create('card');
        card.mount('#card-element'); 

        // Get the appointment ID from the Blade template (passed from the backend)
        const appointmentId = {{ $appointment->id }};  

        // Button click event to generate token
        document.getElementById('generate-token').addEventListener('click', async () => {
            const {error, token} = await stripe.createToken(card);

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                return;
            }

            // Display the token in the <h1> tag
            document.getElementById('token-display').textContent = `Generated Token: ${token.id}`;

            // // Send the token and appointment_id to the backend for payment processing
            // fetch(`/api/stripe/payment/${appointmentId}`, { // Use dynamic appointment ID
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //     },
            //     body: JSON.stringify({
            //         stripeToken: token.id,  // This is the token you should pass
            //         appointment_id: appointmentId, // Pass the dynamic appointment ID
            //     }),
            // })
            // .then(response => response.json())
            // .then(data => console.log(data))
            // .catch(err => console.log(err));
        });
    </script>

</body>
</html>
