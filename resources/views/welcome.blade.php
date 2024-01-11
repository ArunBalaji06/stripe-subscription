<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://js.stripe.com/v3/"></script>
    </head>
    <body class="container pt-4">
        <div class="row col-md-12">
            <div class="col-md-8">

            </div>
            {{-- <div class="col-md-2">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#priceModal">
                    Create Price
                </button>

                <!-- Modal -->
                <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('price-create') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add price</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Name
                                        </label>
                                        <input type="text" name="name" class="form-control" id="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            Price
                                        </label>
                                        <input type="text" name="price" class="form-control" id="price" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3">
                                        <label for="interval" class="form-label">
                                            Interval
                                        </label>
                                        <select name="interval" id="interval" class="form-select" aria-label="Default select example">
                                            <option selected>Open this select menu</option>
                                            <option value="day">Daily</option>
                                            <option value="week">Weekly</option>
                                            <option value="month">Monthly</option>
                                            <option value="year">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-2">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Add customer
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('customer-create') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add customer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Name
                                        </label>
                                        <input type="text" name="name" class="form-control" id="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Email address
                                        </label>
                                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                                        <div id="emailHelp" class="form-text">
                                            We'll never share your email with anyone else.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">
                                            Phone
                                        </label>
                                        <input type="text" name="phone" class="form-control" id="phone" aria-describedby="phoneHelp">
                                        <div id="phoneHelp" class="form-text">
                                            We'll never share your phone with anyone else.
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @isset ($users)
            <div class="row col-md-12 mt-4">
                <h3>Customers</h3>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Stripe ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">Subscription Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sno = 1;
                            @endphp
                            @forelse ($users as $user)
                                <tr>
                                    <th scope="row">{{ $sno }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->stripe_id }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>Need to work</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary">
                                            <a href="{{ route('attach-pm') }}">
                                                Attach payment method
                                            <a>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <a href="{{ route('create-subscription') }}">
                                                Pay subscription
                                            <a>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <a href="{{ route('cancel-subscription') }}">
                                                Cancel subscription
                                            <a>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <a href="{{ route('3d-secure-payment') }}">
                                                3D secure payment
                                            <a>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <a href="{{ route('refund') }}">
                                                Refund
                                            <a>
                                        </button>
                                    </td>
                                </tr>


                                @php $sno++ @endphp
                            @empty
                            <tr>
                                <th scope="row" colspan="6" class="text-center">No data found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endisset

    </body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>

    $('#add-card-button').click(function(e){
        $('#priceModals').show()
    })


    $(document).on('click', '#add-card', function(){
        const stripe = Stripe('pk_test_51O0falSH6gn9s852iQmS9YYOTyRrLcqbkJRQURzwQawJLo1jkUt2tth74hvezftCR8dT9QLb3EqrDwIzO4EOW95j00fn6sfkEu');

        // alert($(this).data('id'))
        var customer_id = $(this).data('id')
        // Create an instance of Elements
        const appearance = {
                theme: 'stripe',
            };

        const options = {
                layout: {
                    type: 'tabs',
                    defaultCollapsed: false,
                }
            };

        const elements = stripe.elements();

        // Create a card element
        const cardElement = elements.create('card');

        // Mount the card element to the '#card-element' div
        cardElement.mount('#card-element'+customer_id);
    })


</script>
