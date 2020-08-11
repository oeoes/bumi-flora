@extends('layouts.master')

@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="b-b">
            <div class="nav-active-border b-primary bottom">
                <ul class="nav" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="item-tab" data-toggle="tab" href="#item3" role="tab" aria-controls="item" aria-selected="true">Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="unit-tab" data-toggle="tab" href="#unit3" role="tab" aria-controls="unit" aria-selected="false">Satuan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="itemtype-tab" data-toggle="tab" href="#itemtype3" role="tab" aria-controls="itemtype" aria-selected="false">Jenis Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="brand-tab" data-toggle="tab" href="#brand3" role="tab" aria-controls="brand" aria-selected="false">Brand</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="item3" role="tabpanel" aria-labelledby="item-tab">
                <p>Item tab</p>
            </div>
            <div class="tab-pane fade" id="unit3" role="tabpanel" aria-labelledby="unit-tab">
                <div class="padding">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Basic form</strong>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputPassword1">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                            <small id="passwordHelp" class="form-text text-muted">Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.</small>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input">
                                                <label class="form-check-label">
                                                    Check me out
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="itemtype3" role="tabpanel" aria-labelledby="itemtype-tab">
                <P>Item type tab</P>
            </div>
            <div class="tab-pane fade" id="brand3" role="tabpanel" aria-labelledby="brand-tab">
                <p>Brand tab</p>
            </div>
        </div>
    </div>
</div>
@endsection