@extends('reviews.layout')

@section('content')

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Laravel</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('reviews.create') }}"> Create New Reviews</a>

            </div>

        </div>

    </div>

    <form action="{{ route('reviews.search') }}" method="GET">
        <input type="text" name="search" placeholder="Recherche">
        <button type="submit">Rechercher</button>
    </form>

    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    <table class="table table-bordered" id="reviews-list">

        <tr>

            <th>No</th>

            <th>Note</th>

            <th>Reviews</th>

            <th>User</th>

            <th>Date</th>

            <th>Company</th>

            <th width="280px">Action</th>

        </tr>

        @php $i = 0 @endphp

        @foreach ($reviews as $review)

        <tr>

            <td>{{ ++$i }}</td>

            <td>{{ $review->note }}</td>

            <td>{{ $review->avis }}</td>

            <td>{{ $review->user->name }}</td>

            <td>{{ $review->dateTime }}</td>

            <td>{{ $review->company->companyName }}</td>

            <td>

                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                    
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>

            </td>

        </tr>

        @endforeach

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<button id="fetchReviewsBtn">Obtenir les avis</button>
<div id="reviewsContainer"></div>

<script>
    $(document).ready(function () {
        $('#fetchReviewsBtn').on('click', function () {
            $.ajax({
                url: '/fetch-reviews', 
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    displayReviews(response.groupedReviews);
                },
                error: function (error) {
                    console.error('Erreur lors de la récupération des avis :', error);
                }
            });
        });
    });

    // Fonction pour afficher les avis dans la section spécifiée
    function displayReviews(reviews) {
        var container = $('#reviewsContainer');

        container.empty();

        $.each(reviews, function (companyName, companyReviews) {
            container.append('<h2>' + companyName + '</h2>');
            
            $.each(companyReviews, function (index, review) {
                container.append('<p><strong>Note:</strong> ' + review.note + ' - ' + review.avis + '</p>');
            });
        });
    }
</script>

@endsection
