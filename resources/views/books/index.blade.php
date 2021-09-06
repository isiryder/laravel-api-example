<html>
<head>
    <title>Laravel Example App</title>
    <meta name="_token" content="{{csrf_token()}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    </div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Book form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="image-upload" method="post" action="{{ route('books.store') }}" enctype="multipart/form-data">
                <div class="modal-body">
                        @csrf
                        <div class="border p-3 m-2">
                            <div class="form-group">
                                <label>Book Id</label>
                                <input readonly type="text" name="book_id" id="book_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Book Name</label>
                                <input required type="text" name="book_name" id="book_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Book Year</label>
                                <input required type="number" name="book_year" id="book_year" class="form-control">
                            </div>
                        </div>
                        <div class="border p-3 m-2">
                            <div class="form-group">
                                <label>Author Id</label>
                                <input readonly type="text" name="author_id" id="author_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Author Name</label>
                                <input required type="text" name="author_name" id="author_name" class="form-control" pattern="^[a-zA-Z ]+$">
                            </div>
                            <div class="form-group">
                                <label>Author Genre</label>
                                <input type="text" name="author_genre" id="author_genre" class="form-control" pattern="^[a-zA-Z ]+$">
                            </div>
                            <div class="form-group">
                                <label>Author Birth Date</label>
                                <input required type="text" name="author_birth_date" id="author_birth_date" class="form-control">
                            </div>
                        </div>
                        <div class="border p-3 m-2">
                            <div class="border p-3 m-2">
                                <div class="form-group">
                                    <label>Library Id</label>
                                    <input readonly type="text" name="library_id" id="library_id" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Library Name</label>
                                    <input type="text" name="library_name" id="library_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Library Address</label>
                                    <input type="text" name="library_address" id="library_address" class="form-control"/>
                                </div>
                                <div class="alert alert-danger">
                                    <div class="alert-danger-box-text"></div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="saveEdit btn btn-success" id="formSubmit">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('#exampleModal').on('show.bs.modal', function(e) {
                let btn = $(e.relatedTarget); // element that opened the modal
                let book_id = '';
                let book_name = ''
                let book_year = ''
                let author_id = '';
                let author_name = ''
                let author_birth_date = ''
                let author_genre = ''
                let library_id = '';
                let library_name = ''
                let library_address = ''

                $(".alert-danger").hide();
                $('.alert-danger-box-text').html('');

                if (btn[0].id == "edit_book") {
                    $('#formSubmit').html('Update');
                    $('#book_id').parent().show()
                    $('#author_id').parent().show()
                    $('#library_id').parent().show()
                    parent_table_row = btn.parent().parent().parent();
                    book_id = btn.data('id');
                    book_name = parent_table_row.find('#book_name_in_row')[0].innerText;
                    book_year = parent_table_row.find('#book_year_in_row')[0].innerText;
                    author_id = btn.data('author-id');
                    author_name = parent_table_row.find('#author_name_in_row')[0].innerText;
                    author_birth_date = parent_table_row.find('#author_birth_date_in_row')[0].innerText;
                    author_genre = parent_table_row.find('#author_genre_in_row')[0].innerText;
                    library_id = btn.data('library-id');
                    library_name = parent_table_row.find('#library_name_in_row')[0].innerText;
                    library_address = parent_table_row.find('#library_address_in_row')[0].innerText;
                } else {
                    $('#formSubmit').html('Save');
                    $('#book_id').parent().hide()
                    $('#author_id').parent().hide()
                    $('#library_id').parent().hide()
                }

                $('#book_id').val(book_id);
                $('#book_name').val(book_name);
                $('#book_year').val(book_year);
                $('#author_id').val(author_id);
                $('#author_name').val(author_name);
                $('#author_birth_date').val(author_birth_date);
                $('#author_genre').val(author_genre);
                $('#library_id').val(library_id);
                $('#library_name').val(library_name);
                $('#library_address').val(library_address);
            })
        })
        $(document).ready(function(){
            $('#author_name').on('input', () => {
                $('#author_name')[0].checkValidity();
            });
            $('#author_genre').on('input', () => {
                $('#author_genre')[0].checkValidity();
            });
            $( "#author_birth_date" ).datepicker({dateFormat: 'yy-mm-dd', maxDate: '0'});
            $(".alert-danger").hide();
            $(".alert-success").hide();
            $('#formSubmit').click(function(e){
                e.preventDefault();
                errors = false;
                message = '';
                if (!$('#book_name')[0].checkValidity()) {
                    errors = true;
                    message += '<li>Book name is required.</li>';

                }
                if (!$('#book_year')[0].checkValidity()) {
                    errors = true;
                    message += '<li>Book year is required.</li>';

                }
                if (!$('#author_name')[0].checkValidity()) {
                    errors = true;
                    message += '<li>Author name is required and should only contain alphabetic characters.</li>';

                }
                if (!$('#author_genre')[0].checkValidity()) {
                    errors = true;
                    message += '<li>Author genre should only contain alphabetic characters.</li>';
                }
                if (!$('#author_birth_date')[0].checkValidity()) {
                    errors = true;
                    message += '<li>Author birth date is required.</li>';
                }
                if (errors) {
                    $('.alert-danger-box-text').html(message);
                    $('.alert-danger').show();
                    return;
                }
                $('.alert-danger-box-text').html('');
                $(".alert-danger").hide();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                let btn = $(e.relatedTarget);
                let url = '';
                if (e.target.innerText == "Update") {
                    url = "{{ route('books.store', ) }}" + "/" + $('#book_id').val();
                    method = "PUT";
                } else {
                    url = "{{ route('books.store') }}";
                    method = "POST";
                }
                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        id: $('#book_id').val(),
                        name: $('#book_name').val(),
                        year: $('#book_year').val(),
                        author: {
                            id: $('#author_id').val(),
                            name: $('#author_name').val(),
                            birth_date: $('#author_birth_date').val(),
                            genre: $('#author_genre').val()
                        },
                        libraries: [{
                            id: $('#library_id').val(),
                            name: $('#library_name').val(),
                            address: $('#library_address').val()
                        }]
                    },
                    success: function(response){
                        if(response && response.errors)
                        {
                            $('.alert-danger').html('');

                            $.each(response.errors, function(key, value){
                                $('.alert-danger').show();
                                $('.alert-danger').append('<li>'+value+'</li>');
                            });
                        }
                        else
                        {
                            location.reload();
                        }
                    },
                    error: function(response){
                        console.log(response);
                        message = "<strong>Error!</strong> " + response.statusText + ": " + response.responseJSON.message;
                            $('.alert-danger-box-text').html(message);
                            $('.alert-danger').show();
                    }
                });
            });
            $('button:disabled').removeAttr('disabled');
        });
    </script>

<div class="container mt-100">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div>
                    <div>
                        <div class="alert alert-success">
                            <div class="alert-box-text"></div>
                        </div>
                        <table class="table table-striped table-bordered" id="example-app">

                    <!-- Table Headings -->
                    <thead>
                        <th style="display: none;">#</th>
                        <th>Book Name</th>
                        <th>Book Year</th>
                        <th>Author Name</th>
                        <th>Author Birth Date</th>
                        <th>Author Genre</th>
                        <th>Library Name</th>
                        <th>Library Address</th>
                        <th><div class="form-group">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" id="add_book">Add&nbsp;Book</button>
                            </div>Actions
                        </th>
                    </thead>
                    @if (count($books) > 0)
                    <!-- Table Body -->
                    <tbody>
                        @foreach ($books as $book)
                            @if (isset($book->libraries))
                                @php
                                    $libraryId = '';
                                    $libraryName = '';
                                    $libraryAddress = '';
                                @endphp
                                @foreach ($book->libraries as $library)
                                @if ($loop->first)
                                    @php 
                                        $libraryId = $library->id;
                                        $libraryName = $library->name;
                                        $libraryAddress = $library->address;
                                    @endphp
                                @endif
                                @endforeach
                            @endif
                            <tr id="row-{{$loop->iteration}}">
                                <th scope="row" style="display: none;">{{$loop->iteration}}</th>
                                <!-- Book Name -->
                                <td>
                                    <div id='book_name_in_row'>{{ $book->name }}</div>
                                </td>
                                <td>
                                    <div id="book_year_in_row">{{ $book->year }}</div>
                                </td>
                                <td>
                                    <div id="author_name_in_row">{{ $book->author?->name }}</div>
                                </td>
                                <td>
                                    <div id="author_birth_date_in_row">{{ $book->author?->birth_date }}</div>
                                </td>
                                <td>
                                    <div id="author_genre_in_row">{{ $book->author?->genre }}</div>
                                </td>
                                <td>
                                    <div id="library_name_in_row">{{ $libraryName }}</div>
                                </td>
                                <td>
                                    <div id="library_address_in_row">{{ $libraryAddress }}</div>
                                </td>
                                <td>
                                <div class="form-group">
                                    <button disabled type="button" class="btn btn-outline-success" data-id="{{ $book->id }}" data-author-id="{{ $book->author?->id ?? '' }}" data-library-id="{{ $libraryId ?? '' }}" data-toggle="modal" data-target="#exampleModal" id="edit_book">Edit</button>
                                </div>
                                <div class="form-group">
                                    <button disabled class="btn btn-outline-danger delete-data-{{$loop->iteration}}">Delete</button>
                                </div>
                                <script>
                                    $(".delete-data-{{$loop->iteration}}").click(function(event){
                                        event.preventDefault();

                                        let name = $("input[name=name]").val();
                                        let year = $("input[name=year]").val();
                                        let _token   = $('meta[name="csrf-token"]').attr('content');
                                        
                                        $.ajax({
                                            url: "{{ route('books.destroy', $book->id) }}",
                                            type:"DELETE",
                                            data:{
                                                name:name,
                                                year:year,
                                                _token: _token
                                            },
                                            success:function(response){
                                                console.log(response);
                                                if(response) {
                                                    message = "<strong>Success!</strong> " + response.message;
                                                    $('.alert-box-text').html(message);
                                                    $('.alert-success').show();
                                                    $('#row-{{$loop->iteration}}').remove();
                                                    setInterval('$(".alert-success").hide()', 3000);
                                                }
                                            },
                                        });
                                    });
                                </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @else
                    <tr><td colspan="10">No books yet, add a new one!</tr></td>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
