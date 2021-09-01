
    <div class="panel-body">


<!-- New Book Form -->
<form action="{{ url('book') }}" method="POST" class="form-horizontal">
    {{ csrf_field() }}

    <!-- Book Name -->
    <div class="form-group">
        <label for="book" class="col-sm-3 control-label">Book</label>

        <div class="col-sm-6">
            <input type="text" name="name" id="book-name" class="form-control">
        </div>
    </div>

    <!-- Add Book Button -->
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-default">
                <i class="fa fa-plus"></i> Add Book
            </button>
        </div>
    </div>
</form>
</div>


