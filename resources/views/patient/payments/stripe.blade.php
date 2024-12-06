<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">

</head>

<body class="bg-gray-100 py-8">

    <div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-center text-gray-700 mb-6">Pay with Stripe</h1>
        <form action="{{ route('stripe.post') }}" method="POST" id="payment-form">
            @csrf

            <input type="hidden" name="appointment_id" value="{{ $encryptedId }}">
            <div class="mb-6">
                <label for="card-element" class="block text-lg font-medium text-gray-600 mb-2">Card Details</label>
                <div id="card-element" class="p-3 border border-gray-300 rounded-md">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
            </div>

            <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>

            <div class="flex justify-center">
                <button type="submit"
                    class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Submit Payment
                </button>
            </div>
        </form>
    </div>

    <script>
        var stripe = Stripe('pk_test_51QSzAgGD49l9BuIFDH9CL99P2OIjFRf0x6a5z6SzzXsVLSStYh29N6KXbGH7HZpoaP3Tq74saUgu3ll9x0IW9zIv00g9gQYprW'); // Your Stripe Public Key
        var elements = stripe.elements();

        var style = {
            base: {
                color: "#32325d",
                lineHeight: "18px",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", { style: style });
        card.mount("#card-element");

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Display error.message in your UI
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server
                    var token = result.token.id;
                    var hiddenTokenInput = document.createElement('input');
                    hiddenTokenInput.setAttribute('type', 'hidden');
                    hiddenTokenInput.setAttribute('name', 'stripeToken');
                    hiddenTokenInput.setAttribute('value', token);
                    form.appendChild(hiddenTokenInput);
                    form.submit();
                }
            });
        });
    </script>

</body>

</html>
