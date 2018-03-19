<html>
<body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    Stripe.setPublishableKey("{{ env('STRIPE_KEY') }}");
    $(document).ready(function() {

        // target the form
        // on form submission, create a token
        $('#subscribe-form').submit(function(e) {
            var form = $(this);

            // disable the form button
            form.find('button').prop('disabled', true);

            Stripe.card.createToken(form, function(status, response) {
                if (response.error) {
                    form.find('.stripe-errors').text(response.error.message).addClass('alert alert-danger');
                    form.find('button').prop('disabled', false);
                } else {
                    console.log(response);

                    // append the token to the form
                    form.append($('<input type="hidden" name="cc_token">').val(response.id));

                    // submit the form
                    form.get(0).submit();
                }
            });

            e.preventDefault();
        });

    });

</script>


<form action="/post" method="POST" id="subscribe-form">
{{csrf_field()}}
    <span class="payment-errors"></span>
    <div class="form-row">
        <label>
            <span>Navn</span>
            <input type="text" size="20" name="name"/>
        </label>
    </div>

    <div class="form-row">
        <label>
            <span>email</span>
            <input type="text" size="20" name="email"/>
        </label>
    </div>

    <div class="form-row">
        <label>
            <span>Card Number</span>
            <input type="text" size="20" data-stripe="number"/>
        </label>
    </div>

    <div class="form-row">
        <label>
            <span>CVC</span>
            <input type="text" size="4" data-stripe="cvc"/>
        </label>
    </div>

    <div class="form-row">
        <label>
            <span>Expiration (MM/YYYY)</span>
            <input type="text" size="2" data-stripe="exp-month"/>
        </label>
        <span> / </span>
        <input type="text" size="4" data-stripe="exp-year"/>
    </div>

    <button type="submit">Submit Payment</button>
</form>
</body>
</html>