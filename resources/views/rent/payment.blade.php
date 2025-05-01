<h2>Redirecting to Payment...</h2>

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetch("{{ route('rent.payment.session') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                item_id: {{ $item->id }},
                start_date: "{{ $start_date }}",
                end_date: "{{ $end_date }}",
                delivery_address: "{{ $delivery_address }}",
                rental_id: {{ $rent->id }}
            }),
        })
        .then(response => response.json())
        .then(sessionData => {
            if (sessionData.id) {
                var stripe = Stripe("{{ config('services.stripe.key') }}");
                return stripe.redirectToCheckout({
                    sessionId: sessionData.id
                });
            }
            throw new Error('Session ID not found');
        })
        .catch(function (error) {
            console.error('Error:', error);
            alert("There was an error during the payment process. Please try again.");
        });
    });
</script>
