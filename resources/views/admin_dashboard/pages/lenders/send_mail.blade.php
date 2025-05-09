@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<h2>Send Email to {{ $lender->first_name }} {{ $lender->last_name }} </h2>
<form action="{{ route('lender.mail',$lender->id) }}" method="POST">
        @csrf

       
        <div class="mb-3 form-group">
            <label for="greeting">Greeting</label>
            <input type="text" name="greeting" class="form-control"  required>
        </div>

       
        <div class="mb-3 form-group">
            <label for="body">Mail_body</label>
            <textarea name="body" class="form-control"></textarea>
        </div>

        
        <div class="mb-3 form-group">
            <label for="action_text">Action Text</label>
            <input type="text" name="action_text" class="form-control" required>
        </div>

        <div class="mb-3 form-group">
            <label for="action_url">Action Url</label>
            <input type="text" name="action_url" class="form-control" required>
        </div>

        <div class="mb-3 form-group">
            <label for="endline">End line</label>
            <input type="text" name="endline" class="form-control" required>
        </div>



        
       


        {{-- Submit Button --}}
        <button type="submit" class="btn btn-success">Send mail</button>

    </form>
@endsection
